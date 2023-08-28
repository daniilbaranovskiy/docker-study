<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: ProductRepository::class)]
class Product implements JsonSerializable
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
    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Name cannot be blank")]
    #[Assert\Length(max: 255, maxMessage: "Name is too long")]
    private ?string $name = null;

    /**
     * @var string|null
     */
    #[Assert\NotBlank(message: "Price cannot be blank")]
    #[Assert\Positive(message: "Price must be a positive number")]
    #[ORM\Column(type: Types::DECIMAL, precision: 4, scale: '0')]
    private ?string $price = null;

    /**
     * @var string|null
     */
    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Description cannot be blank.")]
    #[Assert\Length(max: 500, maxMessage: "Description cannot be longer than {{ limit }} characters.")]
    private ?string $description = null;

    /**
     * @var int|null
     */
    #[ORM\Column]
    #[Assert\NotBlank(message: "Quantity cannot be blank")]
    #[Assert\Positive(message: "Quantity must be a positive number")]
    private ?int $quantity = null;

    /**
     * @var string|null
     */
    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Color cannot be blank")]
    #[Assert\Length(max: 255, maxMessage: "Color is too long")]
    private ?string $color = null;

    /**
     * @var Category|null
     */
    #[ORM\ManyToOne(targetEntity: Category::class, inversedBy: "products")]
    #[Assert\NotBlank(message: "Category cannot be blank")]
    private ?Category $category = null;

    /**
     * @var Collection
     */
    #[ORM\ManyToMany(targetEntity: Orders::class, mappedBy: "products")]
    private Collection $orders;

    /**
     * Product constructor
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
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return $this
     */
    public function setName(string $name): self
    {
        $this->name = $name;

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
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param string $description
     * @return $this
     */
    public function setDescription(string $description): self
    {
        $this->description = $description;

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
     * @return Category|null
     */
    public function getCategory(): ?Category
    {
        return $this->category;
    }

    /**
     * @param Category|null $category
     * @return $this
     */
    public function setCategory(?Category $category): self
    {
        $this->category = $category;

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
     * @param Orders $order
     * @return $this
     */
    public function addOrder(Orders $order): self
    {
        if (!$this->orders->contains($order)) {
            $this->orders[] = $order;
            $order->addProduct($this);
        }

        return $this;
    }

    /**
     * @return array
     */
    public function jsonSerialize(): array
    {
        return [
            "id" => $this->getId(),
            "name" => $this->getName(),
            "price" => $this->getPrice(),
            "description" => $this->getDescription(),
            "quantity" => $this->getQuantity(),
            "color" => $this->getColor(),
            "category" => $this->getCategory(),
        ];
    }
}
