<?php

namespace App\Entity;

use App\Repository\OrdersRepository;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;

#[ORM\Entity(repositoryClass: OrdersRepository::class)]
class Orders implements JsonSerializable
{
    /**
     * @var int|null
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * @var DateTimeInterface|null
     */
    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?DateTimeInterface $orderDate = null;

    /**
     * @var string|null
     */
    #[ORM\Column(type: Types::DECIMAL, precision: 6, scale: 0)]
    private ?string $orderSum = null;

    /**
     * @var Collection
     */
    #[ORM\ManyToMany(targetEntity: Car::class, inversedBy: "orders")]
    private Collection $cars;

    /**
     * Car constructor
     */
    public function __construct()
    {
        $this->cars = new ArrayCollection();
    }

    /**
     * @var User|null
     */
    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: "user")]
    private ?User $user = null;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return DateTimeInterface|null
     */
    public function getOrderDate(): ?DateTimeInterface
    {
        return $this->orderDate;
    }

    /**
     * @param DateTimeInterface $orderDate
     * @return $this
     */
    public function setOrderDate(DateTimeInterface $orderDate): self
    {
        $this->orderDate = $orderDate;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getOrderSum(): ?string
    {
        return $this->orderSum;
    }

    /**
     * @param string $orderSum
     * @return $this
     */
    public function setOrderSum(string $orderSum): self
    {
        $this->orderSum = $orderSum;

        return $this;
    }

    /**
     * @return Collection
     */
    public function getCars(): Collection
    {
        return $this->cars;
    }

    /**
     * @param Collection $cars
     * @return $this
     */
    public function setCars(Collection $cars): self
    {
        $this->cars = $cars;

        return $this;
    }

    /**
     * @return User|null
     */
    public function getUser(): ?User
    {
        return $this->user;
    }

    /**
     * @param User|null $user
     * @return Orders
     */
    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @param Car $car
     * @return $this
     */
    public function addCar(Car $car): self
    {
        if (!$this->cars->contains($car)) {
            $this->cars[] = $car;
            $car->addOrder($this);
        }

        return $this;
    }

    public function jsonSerialize(): array
    {
        $carIds = [];
        foreach ($this->getCars() as $car) {
            $carIds[] = $car->getId();
        }

        return [
            'id' => $this->getId(),
            'order_date' => $this->getOrderDate()->format('Y-m-d H:i'),
            'order_sum' => $this->getOrderSum(),
            'car_ids' => $carIds,
            'user_id' => $this->getUser()
        ];
    }
}
