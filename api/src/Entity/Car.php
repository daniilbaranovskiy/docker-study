<?php

namespace App\Entity;

use App\Repository\CarRepository;
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
    #[ORM\Column(length: 255)]
    private ?string $year = null;

    /**
     * @var string|null
     */
    #[ORM\Column(type: Types::DECIMAL, precision: 5, scale: '0')]
    private ?string $price = null;

    #[ORM\Column]
    private ?int $quantity = null;

    /**
     * @var string|null
     */
    #[ORM\Column(length: 255)]
    private ?string $fuel_type = null;

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
     * @var Model|null
     */
    #[ORM\ManyToOne(targetEntity: Model::class, inversedBy: "cars")]
    private ?Model $model = null;

    /**
     * @var Collection
     */
    #[ORM\OneToMany(mappedBy: "car", targetEntity: Order::class)]
    private Collection $car;

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
    public function setYear(string $year): static
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
    public function setPrice(string $price): static
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
    public function setQuantity(int $quantity): static
    {
        $this->quantity = $quantity;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getFuelType(): ?string
    {
        return $this->fuel_type;
    }

    /**
     * @param string $fuel_type
     * @return $this
     */
    public function setFuelType(string $fuel_type): static
    {
        $this->fuel_type = $fuel_type;

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
    public function setTransmission(string $transmission): static
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
    public function setColor(string $color): static
    {
        $this->color = $color;

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
     * @return void
     */
    public function setModel(?Model $model): void
    {
        $this->model = $model;
    }

    /**
     * @return Collection
     */
    public function getCar(): Collection
    {
        return $this->car;
    }

    /**
     * @param Collection $car
     * @return void
     */
    public function setCar(Collection $car): void
    {
        $this->car = $car;
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
            'color' => $this->getColor()
        ];
    }
}
