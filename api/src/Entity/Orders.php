<?php

namespace App\Entity;

use App\Repository\OrdersRepository;
use App\Validator\Constraints\OrdersConstraints;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: OrdersRepository::class)]
#[OrdersConstraints]
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
    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Assert\NotBlank(message: "Order date cannot be blank")]
    private ?DateTimeInterface $orderDate = null;

    /**
     * @var string|null
     */
    #[ORM\Column(type: Types::DECIMAL, precision: 4, scale: '0')]
    #[Assert\Positive(message: "Order sum must be a positive number")]
    private ?string $orderSum = null;

    /**
     * @var string|null
     */
    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Status cannot be blank")]
    #[Assert\Choice(choices: ["pending", "completed", "cancelled"], message: "Invalid status")]
    private ?string $status = null;

    /**
     * @var User|null
     */
    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: "user")]
    private ?User $user = null;

    /**
     * @var Collection
     */
    #[ORM\ManyToMany(targetEntity: Product::class, inversedBy: "orders")]
    private Collection $products;

    /**
     * Orders constructor
     */
    public function __construct()
    {
        $this->products = new ArrayCollection();
    }

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
     * @return string|null
     */
    public function getStatus(): ?string
    {
        return $this->status;
    }

    /**
     * @param string $status
     * @return $this
     */
    public function setStatus(string $status): self
    {
        $this->status = $status;

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
     * @return $this
     */
    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection
     */
    public function getProducts(): Collection
    {
        return $this->products;
    }

    /**
     * @param Collection $products
     * @return $this
     */
    public function setProducts(Collection $products): self
    {
        $this->products = $products;

        return $this;
    }

    /**
     * @param Product $product
     * @return $this
     */
    public function addProduct(Product $product): self
    {
        if (!$this->products->contains($product)) {
            $this->products[] = $product;
            $product->addOrder($this);
        }

        return $this;
    }

    /**
     * @param Product $product
     * @return $this
     */
    public function removeProduct(Product $product): self
    {
        if ($this->products->contains($product)) {
            $this->products->removeElement($product);
        }

        return $this;
    }

    /**
     * @return array
     */
    public
    function jsonSerialize(): array
    {
        $productIds = [];
        foreach ($this->getProducts() as $product) {
            $productIds[] = $product->getId();
        }
        return [
            "id" => $this->getId(),
            "order_date" => $this->getOrderDate()->format('Y-m-d H:i:s'),
            "order_sum" => $this->getOrderSum(),
            "status" => $this->getStatus(),
            "product_ids" => $productIds,
            "user_id" => $this->getUser()
        ];
    }
}
