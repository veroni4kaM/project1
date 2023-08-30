<?php

namespace App\Entity;

use App\Repository\TransactionRepository;
use DateTimeInterface;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;
use Symfony\Component\Validator\Constraints as Assert;
use ApiPlatform\Core\Annotation\ApiResource;


#[ORM\Entity(repositoryClass: TransactionRepository::class)]
#[ApiResource(collectionOperations: [
    "get" => [
        "method" => "GET",
        "security" => "is_granted('" . User::ROLE_USER . "') or is_granted('" . User::ROLE_ADMIN . "')"
    ],
    "post" => [
        "method" => "POST",
        "security" => "is_granted('" . User::ROLE_USER . "')"
    ]
],
    itemOperations: [
        "get" => [
            "method" => "GET",
        ],
        "put" => [
            "method" => "PUT",
            "security" => "is_granted('" . User::ROLE_USER . "')"
        ],
        "delete" => [
            "method" => "DELETE",
            "security" => "is_granted('" . User::ROLE_USER . "')"
        ]
    ],
       attributes: [
            "security" => "is_granted('" . User::ROLE_USER . "') or is_granted('" . User::ROLE_ADMIN . "')"
        ]
)]
class Transaction implements JsonSerializable
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
     * @Assert\Regex(
     *      pattern="/^\d+(\.\d{1,2})?$/",
     *      message="Amount must be a valid decimal number with up to 2 decimal places.")
     */
    #[ORM\Column(type: Types::DECIMAL, precision: 20, scale: 2)]
    #[Assert\NotBlank]
    private ?string $amount = null;

    /**
     * @var DateTimeInterface|null
     */
    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Assert\NotBlank]
    private ?DateTimeInterface $transactionDate = null;

    /**
     * @var bool|null
     */
    #[ORM\Column]
    #[Assert\NotNull]
    private ?bool $isDeposit = null;

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
    public function getAmount(): ?string
    {
        return $this->amount;
    }

    /**
     * @param string $amount
     * @return $this
     */
    public function setAmount(string $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * @return DateTimeInterface|null
     */
    public function getTransactionDate(): ?DateTimeInterface
    {
        return $this->transactionDate;
    }

    /**
     * @param DateTimeInterface $transactionDate
     * @return $this
     */
    public function setTransactionDate(DateTimeInterface $transactionDate): self
    {
        $this->transactionDate = $transactionDate;

        return $this;
    }


    /**
     * @return array
     */
    public function jsonSerialize(): array
    {
        return [
            "id" => $this->getId(),
            "amount" => $this->getAmount(),
            "transaction_date" => $this->getTransactionDate(),
            "is_deposit" => $this->getIsDeposit(),
            //"account" => $this->getAccount()
        ];
    }

    public function getIsDeposit(): ?bool
    {
        return $this->isDeposit;
    }

    public function setIsDeposit(?bool $isDeposit): void
    {
        $this->isDeposit = $isDeposit;
    }
}
