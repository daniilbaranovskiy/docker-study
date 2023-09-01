<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\RangeFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use App\Action\CreateProductAction;
use App\EntityListener\ProductEntityListener;
use App\Repository\ProductRepository;
use App\Validator\Constraints\ProductConstraints;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints\NotBlank;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
#[ProductConstraints]
#[ApiResource(
    collectionOperations: [
        "get" => [
            "method" => "GET",
            "security" => "is_granted('" . User::ROLE_USER . "')",
            "normalization_context" => ["groups" => ["get:collection:product"]]
        ],
        "post" => [
            "method" => "POST",
            "security" => "is_granted('" . User::ROLE_USER . "')",
            "denormalization_context" => ["groups" => ["post:collection:product"]],
            "normalization_context" => ["groups" => ["get:item:product"]],
            "controller" => CreateProductAction::class
        ]
    ],
    itemOperations: [
        "get" => [
            "method" => "GET",
            "normalization_context" => ["groups" => ["get:item:product"]]
        ]
    ],
    attributes: [
        "security" => "is_granted('" . User::ROLE_ADMIN . "') or is_granted('" . User::ROLE_USER . "')"
    ]
)]
#[ApiFilter(SearchFilter::class, properties: [
    "name" => "partial",
    "description"
])]
#[ApiFilter(RangeFilter::class, properties: ['price'])]
#[ORM\EntityListeners([ProductEntityListener::class])]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups([
        "get:item:product"
    ])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[NotBlank]
    #[Groups([
        "get:collection:product",
        "get:item:product",
        "post:collection:product"
    ])]
    private ?string $name = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 2, scale: '0')]
    #[Groups([
        "get:item:product",
        "post:collection:product"
    ])]
    private ?string $price = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups([
        "get:item:product",
        "post:collection:product"
    ])]
    private ?string $description = null;

    #[ORM\ManyToOne(targetEntity: Category::class, inversedBy: "products")]
    #[Groups([
        "get:item:product",
        "post:collection:product"
    ])]
    private ?Category $category = null;

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
    public function setName(string $name): static
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
    public function setPrice(string $price): static
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
     * @param string|null $description
     * @return $this
     */
    public function setDescription(?string $description): static
    {
        $this->description = $description;

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
     * @return Product
     */
    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }
}