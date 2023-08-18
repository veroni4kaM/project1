<?php

namespace App\Entity;

use App\Repository\TransactionRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TransactionRepository::class)]
class Transaction
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 15, scale: 2)]
    private ?string $amount = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $transaction_date = null;

    #[ORM\ManyToOne(targetEntity: Account::class)]
    #[ORM\JoinColumn(name: "from_account_id",referencedColumnName: "id")]
    private $fromAccount;
    #[ORM\ManyToOne(targetEntity: Recipient::class)]
    #[ORM\JoinColumn(name: "to_account_id",referencedColumnName: "id")]
    private $toAccount;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAmount(): ?string
    {
        return $this->amount;
    }

    public function setAmount(string $amount): static
    {
        $this->amount = $amount;

        return $this;
    }

    public function getTransactionDate(): ?\DateTimeInterface
    {
        return $this->transaction_date;
    }

    public function setTransactionDate(\DateTimeInterface $transaction_date): static
    {
        $this->transaction_date = $transaction_date;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getFromAccount()
    {
        return $this->fromAccount;
    }

    /**
     * @param mixed $fromAccount
     */
    public function setFromAccount($fromAccount): void
    {
        $this->fromAccount = $fromAccount;
    }

    /**
     * @return mixed
     */
    public function getToAccount()
    {
        return $this->toAccount;
    }

    /**
     * @param mixed $toAccount
     */
    public function setToAccount($toAccount): void
    {
        $this->toAccount = $toAccount;
    }
}
