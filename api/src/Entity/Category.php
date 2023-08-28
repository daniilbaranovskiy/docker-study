<?php

namespace App\Entity;

use App\Repository\CategoryRepository;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: CategoryRepository::class)]
#[UniqueEntity(fields: ['name'], message: 'This name is already taken.')]
class Category implements JsonSerializable
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
    #[Assert\NotBlank(message: "Name cannot be blank.")]
    #[Assert\Length(max: 255, maxMessage: "Name cannot be longer than {{ limit }} characters.")]
    #[Assert\Regex(pattern: '/^[a-zA-Z0-9\s]+$/', message: "Name can only contain letters, digits, and spaces.")]
    private ?string $name = null;

    /**
     * @var string|null
     */
    #[ORM\Column(length: 500)]
    #[Assert\NotBlank(message: "Description cannot be blank.")]
    #[Assert\Length(max: 500, maxMessage: "Description cannot be longer than {{ limit }} characters.")]
    #[Assert\Regex(pattern: '/^[a-zA-Z0-9\s]+$/', message: "Description can only contain letters, digits, and spaces.")]
    private ?string $description = null;

    /**
     * @var DateTimeInterface|null
     */
    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Assert\NotBlank(message: "CreatedAt cannot be blank")]
    private ?DateTimeInterface $createdAt = null;

    /**
     * @var DateTimeInterface|null
     */
    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Assert\NotBlank(message: "UpdatedAt cannot be blank")]
    private ?DateTimeInterface $updatedAt = null;

    /**
     * @var Collection
     */
    #[ORM\OneToMany(mappedBy: "category", targetEntity: Product::class)]
    private Collection $products;

    /**
     * Category constructor
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
     * @return DateTimeInterface|null
     */
    public function getCreatedAt(): ?DateTimeInterface
    {
        return $this->createdAt;
    }

    /**
     * @param DateTimeInterface $createdAt
     * @return $this
     */
    public function setCreatedAt(DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @return DateTimeInterface|null
     */
    public function getUpdatedAt(): ?DateTimeInterface
    {
        return $this->updatedAt;
    }

    /**
     * @param DateTimeInterface $updatedAt
     * @return $this
     */
    public function setUpdatedAt(DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

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
     * @return array
     */
    public function jsonSerialize(): array
    {
        return [
            "id" => $this->getId(),
            "name" => $this->getName(),
            "description" => $this->getDescription(),
            "createdAt" => $this->getCreatedAt()->format('Y-m-d H:i:s'),
            "updatedAt" => $this->getUpdatedAt()->format('Y-m-d H:i:s')
        ];
    }
}
