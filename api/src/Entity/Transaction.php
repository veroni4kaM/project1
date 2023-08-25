<?php

namespace App\Entity;

use App\Repository\TransactionRepository;
use DateTimeInterface;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;

#[ORM\Entity(repositoryClass: TransactionRepository::class)]
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
     */
    #[ORM\Column(type: Types::DECIMAL, precision: 20, scale: 2)]
    private ?string $amount = null;

    /**
     * @var DateTimeInterface|null
     */
    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?DateTimeInterface $transactionDate = null;

    /**
     * @var bool|null
     */
    #[ORM\Column]
    private ?bool $isDeposit = null;

    /**
     * @var Account|null
     */
    #[ORM\ManyToOne(targetEntity: Account::class, inversedBy: "transaction")]
    private ?Account $account = null;

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
     * @return Account|null
     */
    public function getAccount(): ?Account
    {
        return $this->account;
    }

    /**
     * @param Account|null $account
     * @return $this
     */
    public function setAccount(?Account $account): self
    {
        $this->account = $account;
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
            "account" => $this->getAccount()
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
