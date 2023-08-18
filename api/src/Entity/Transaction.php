<?php

namespace App\Entity;

use App\Repository\TransactionRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TransactionRepository::class)]
class Transaction
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
    #[ORM\Column(type: Types::DECIMAL, precision: 15, scale: 2)]
    private ?string $amount = null;

    /**
     * @var \DateTimeInterface|null
     */
    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $transaction_date = null;

    /**
     * @var
     */
    #[ORM\ManyToOne(targetEntity: Account::class)]
    #[ORM\JoinColumn(name: "from_account_id",referencedColumnName: "id")]
    private $fromAccount;
    /**
     * @var
     */
    #[ORM\ManyToOne(targetEntity: Recipient::class)]
    #[ORM\JoinColumn(name: "to_account_id",referencedColumnName: "id")]
    private $toAccount;

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
    public function setAmount(string $amount): static
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * @return \DateTimeInterface|null
     */
    public function getTransactionDate(): ?\DateTimeInterface
    {
        return $this->transaction_date;
    }

    /**
     * @param \DateTimeInterface $transaction_date
     * @return $this
     */
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
    public function setFromAccount($fromAccount): self
    {
        $this->fromAccount = $fromAccount;
        return $this;
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
    public function setToAccount($toAccount): self
    {
        $this->toAccount = $toAccount;
        return $this;

    }
}
