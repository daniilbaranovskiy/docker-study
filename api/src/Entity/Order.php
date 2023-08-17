<?php

namespace App\Entity;

use App\Repository\OrderRepository;
use DateTimeInterface;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;

#[ORM\Entity(repositoryClass: OrderRepository::class)]
class Order implements JsonSerializable
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?DateTimeInterface $order_date = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 6, scale: '0')]
    private ?string $order_sum = null;

    #[ORM\ManyToOne(targetEntity: Car::class, inversedBy: "car")]
    private ?Car $car = null;
    #[ORM\ManyToOne(targetEntity: Customer::class, inversedBy: "customer")]
    private ?Customer $customer = null;

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
        return $this->order_date;
    }

    /**
     * @param DateTimeInterface $order_date
     * @return $this
     */
    public function setOrderDate(DateTimeInterface $order_date): static
    {
        $this->order_date = $order_date;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getOrderSum(): ?string
    {
        return $this->order_sum;
    }

    /**
     * @param string $order_sum
     * @return $this
     */
    public function setOrderSum(string $order_sum): static
    {
        $this->order_sum = $order_sum;

        return $this;
    }

    /**
     * @return Car|null
     */
    public function getCar(): ?Car
    {
        return $this->car;
    }

    /**
     * @param Car|null $car
     * @return void
     */
    public function setCar(?Car $car): void
    {
        $this->car = $car;
    }

    /**
     * @return Customer|null
     */
    public function getCustomer(): ?Customer
    {
        return $this->customer;
    }

    /**
     * @param Customer|null $customer
     * @return void
     */
    public function setCustomer(?Customer $customer): void
    {
        $this->customer = $customer;
    }

    /**
     * @return array
     */
    public function jsonSerialize(): array
    {
        return [
            'id' => $this->getId(),
            'order_date' => $this->getOrderDate(),
            'order_sum' => $this->getOrderSum(),
            'car_id' => $this->getCar(),
            'customer_id' => $this->getCustomer()
        ];
    }
}
