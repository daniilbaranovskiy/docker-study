<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\ProductRepository;
use App\Validator\Constraints\ProductConstraints;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: ProductRepository::class)]
#[ProductConstraints]
#[ApiResource(
    collectionOperations: [
        "get" => [
            "method" => "GET",
        ],
        "post" => [
            "method" => "POST",
            "security" => "is_granted('" . User::ROLE_ADMIN . "')"
        ],
    ],
    itemOperations: [
        "get" => [
            "method" => "GET"
        ],
        "put" => [
            "method" => "PUT",
            "security" => "is_granted('" . User::ROLE_ADMIN . "')"
        ],
        "delete" => [
            "method" => "DELETE",
            "security" => "is_granted('" . User::ROLE_ADMIN . "')"
        ],
    ],
)]
class Product
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
    #[Assert\Length(max: 255, maxMessage: "Name cannot be longer than {{ limit }} characters.")]
    private ?string $name = null;

    /**
     * @var string|null
     */
    #[ORM\Column(length: 500)]
    #[Assert\NotBlank(message: "Description cannot be blank.")]
    #[Assert\Length(max: 500, maxMessage: "Description cannot be longer than {{ limit }} characters.")]
    private ?string $description = null;

    /**
     * @var string|null
     */
    #[ORM\Column(type: Types::DECIMAL, precision: 4, scale: '0')]
    #[Assert\NotBlank(message: "Price cannot be blank")]
    #[Assert\Positive(message: "Price must be a positive number")]
    private ?string $price = null;

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
    #[Assert\NotBlank(message: "Memory cannot be blank")]
    private ?string $memory = null;

    /**
     * @var string|null
     */
    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Color cannot be blank")]
    #[Assert\Length(max: 255, maxMessage: "Color is too long")]
    private ?string $color = null;

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
    public function getMemory(): ?string
    {
        return $this->memory;
    }

    /**
     * @param string $memory
     * @return $this
     */
    public function setMemory(string $memory): self
    {
        $this->memory = $memory;

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
}
