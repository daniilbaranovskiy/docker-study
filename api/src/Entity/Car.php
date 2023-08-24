<?php

namespace App\Entity;

use App\Repository\CarRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;

#[ORM\Entity(repositoryClass: CarRepository::class)]
class Car implements JsonSerializable
{
    /**
     * @var int|null
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * @var string|null
     */
    #[ORM\Column(type: Types::DECIMAL, precision: 4, scale: 0)]
    private ?string $year = null;

    /**
     * @var string|null
     */
    #[ORM\Column(type: Types::DECIMAL, precision: 6, scale: 0)]
    private ?string $price = null;

    /**
     * @var int|null
     */
    #[ORM\Column]
    private ?int $quantity = null;

    /**
     * @var string|null
     */
    #[ORM\Column(length: 255)]
    private ?string $fuelType = null;

    /**
     * @var string|null
     */
    #[ORM\Column(length: 255)]
    private ?string $transmission = null;

    /**
     * @var string|null
     */
    #[ORM\Column(length: 255)]
    private ?string $color = null;

    /**
     * @var int|null
     */
    #[ORM\Column]
    private ?int $horsepower = null;

    /**
     * @var Model|null
     */
    #[ORM\ManyToOne(targetEntity: Model::class, inversedBy: "cars")]
    private ?Model $model = null;

    /**
     * @var Collection
     */
    #[ORM\ManyToMany(targetEntity: Orders::class, mappedBy: "cars")]
    private Collection $orders;

    /**
     * Car constructor
     */
    public function __construct()
    {
        $this->orders = new ArrayCollection();
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getYear(): ?string
    {
        return $this->year;
    }

    /**
     * @param string $year
     * @return $this
     */
    public function setYear(string $year): self
    {
        $this->year = $year;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getPrice(): ?string
    {
        return $this->price;
    }

    /**
     * @param string $price
     * @return $this
     */
    public function setPrice(string $price): self
    {
        $this->price = $price;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    /**
     * @param int $quantity
     * @return $this
     */
    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getFuelType(): ?string
    {
        return $this->fuelType;
    }

    /**
     * @param string $fuelType
     * @return $this
     */
    public function setFuelType(string $fuelType): self
    {
        $this->fuelType = $fuelType;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getTransmission(): ?string
    {
        return $this->transmission;
    }

    /**
     * @param string $transmission
     * @return $this
     */
    public function setTransmission(string $transmission): self
    {
        $this->transmission = $transmission;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getColor(): ?string
    {
        return $this->color;
    }

    /**
     * @param string $color
     * @return $this
     */
    public function setColor(string $color): self
    {
        $this->color = $color;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getHorsepower(): ?int
    {
        return $this->horsepower;
    }

    /**
     * @param int $horsepower
     * @return $this
     */
    public function setHorsepower(int $horsepower): self
    {
        $this->horsepower = $horsepower;

        return $this;
    }

    /**
     * @return Model|null
     */
    public function getModel(): ?Model
    {
        return $this->model;
    }

    /**
     * @param Model|null $model
     * @return Car
     */
    public function setModel(?Model $model): self
    {
        $this->model = $model;

        return $this;
    }

    /**
     * @return Collection
     */
    public function getOrders(): Collection
    {
        return $this->orders;
    }

    /**
     * @param Collection $orders
     * @return $this
     */
    public function setOrders(Collection $orders): self
    {
        $this->orders = $orders;

        return $this;
    }

    /**
     *
     * @param Orders $order
     * @return $this
     */
    public function addOrder(Orders $order): self
    {
        if (!$this->orders->contains($order)) {
            $this->orders[] = $order;
            $order->addCar($this);
        }

        return $this;
    }

    /**
     * @return array
     */
    public function jsonSerialize(): array
    {
        return [
            'id' => $this->getId(),
            'model' => $this->getModel(),
            'year' => $this->getYear(),
            'price' => $this->getPrice(),
            'quantity' => $this->getQuantity(),
            'fuel_type' => $this->getFuelType(),
            'transmission' => $this->getTransmission(),
            'color' => $this->getColor(),
            'horsepower' => $this->getHorsepower()
        ];
    }
}
