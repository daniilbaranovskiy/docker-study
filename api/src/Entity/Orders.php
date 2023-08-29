<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\OrdersRepository;
use App\Validator\Constraints\OrdersConstraints;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: OrdersRepository::class)]
#[OrdersConstraints]
#[ApiResource(
    collectionOperations: [
        "get" => [
            "method" => "GET",
        ],
        "post" => [
            "method" => "POST",
        ],
    ],
    itemOperations: [
        "get" => [
            "method" => "GET",
        ],
        "put" => [
            "method" => "PUT",
        ],
        "delete" => [
            "method" => "DELETE",
        ]
    ], attributes: [
    "security" => "is_granted('" . User::ROLE_ADMIN . "') or is_granted('" . User::ROLE_USER . "')"
]
)]
class Orders
{
    /**
     * @var int|null
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * @var int|null
     */
    #[ORM\Column]
    #[Assert\NotBlank(message: "ProductId cannot be blank")]
    #[Assert\Positive(message: "ProductId must be a positive number")]
    private ?int $productId = null;

    /**
     * @var int|null
     */
    #[ORM\Column]
    #[Assert\NotBlank(message: "UserId cannot be blank")]
    #[Assert\Positive(message: "UserId must be a positive number")]
    private ?int $userId = null;

    /**
     * @var string|null
     */
    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Address cannot be blank")]
    #[Assert\Length(max: 255, maxMessage: "Address cannot be longer than {{ limit }} characters.")]
    private ?string $address = null;

    /**
     * @var string|null
     */
    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Payment method cannot be blank")]
    #[Assert\Choice(choices: ["card", "cash"], message: "Invalid memory payment method")]
    private ?string $paymentMethod = null;

    /**
     * @var string|null
     */
    #[ORM\Column(type: Types::DECIMAL, precision: 4, scale: '0')]
    #[Assert\NotBlank(message: "Order sum cannot be blank")]
    #[Assert\Positive(message: "Order sum be a positive number")]
    private ?string $orderSum = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return int|null
     */
    public function getProductId(): ?int
    {
        return $this->productId;
    }

    /**
     * @param int $productId
     * @return $this
     */
    public function setProductId(int $productId): self
    {
        $this->productId = $productId;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getUserId(): ?int
    {
        return $this->userId;
    }

    /**
     * @param int $userId
     * @return $this
     */
    public function setUserId(int $userId): self
    {
        $this->userId = $userId;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getAddress(): ?string
    {
        return $this->address;
    }

    /**
     * @param string $address
     * @return $this
     */
    public function setAddress(string $address): self
    {
        $this->address = $address;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getPaymentMethod(): ?string
    {
        return $this->paymentMethod;
    }

    /**
     * @param string $paymentMethod
     * @return $this
     */
    public function setPaymentMethod(string $paymentMethod): self
    {
        $this->paymentMethod = $paymentMethod;

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
}
