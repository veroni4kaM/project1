<?php

namespace App\Entity;

use App\Repository\AccountRepository;
use DateTimeInterface;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;
use App\Validator\Constraints\AccountConstraint;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;


#[ORM\Entity(repositoryClass: AccountRepository::class)]
#[AccountConstraint]
class Account implements JsonSerializable
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * @var string|null
     */
    #[ORM\Column(type: Types::DECIMAL, precision: 15, scale: 2)]
    #[NotBlank]
    private ?string $balance = null;

    /**
     * @var DateTimeInterface|null
     */
    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[NotBlank]
    private ?DateTimeInterface $openDate = null;

    /**
     * @var string|null
     */
    #[ORM\Column(type: Types::DECIMAL, precision: 16, scale: '0')]
    #[NotBlank]
    private ?string $accountNumber = null;


    /**
     * @var User|null
     */
    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: "account")]
    private ?User $user = null;

    /**
     * @var Collection
     */
    #[ORM\OneToMany(mappedBy: "account", targetEntity: Transaction::class)]
    private Collection $transactions;


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
    public function getBalance(): ?string
    {
        return $this->balance;
    }

    /**
     * @param string $balance
     * @return $this
     */
    public function setBalance(string $balance): self
    {
        $this->balance = $balance;

        return $this;
    }

    /**
     * @return DateTimeInterface|null
     */
    public function getOpenDate(): ?DateTimeInterface
    {
        return $this->openDate;
    }


    /**
     * @param DateTimeInterface $openDate
     * @return $this
     */
    public function setOpenDate(DateTimeInterface $openDate): self
    {
        $this->openDate = $openDate;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getAccountNumber(): ?string
    {
        return $this->accountNumber;
    }


    /**
     * @param string $accountNumber
     * @return $this
     */
    public function setAccountNumber(string $accountNumber): self
    {
        $this->accountNumber = $accountNumber;

        return $this;
    }


    /**
     * @return array
     */
    public function jsonSerialize()
    {
        return [
            "id" => $this->getId(),
            "balance" => $this->getBalance(),
            "open_data" => $this->getOpenDate(),
            "account_number" => $this->getAccountNumber(),
            "user" => $this->getUser()
        ];
    }

    /**
     * @return Collection
     */
    public function getTransactions(): Collection
    {
        return $this->transactions;
    }

    /**
     * @param Collection $transactions
     * @return $this
     */
    public function setTransactions(Collection $transactions): self
    {
        $this->transactions = $transactions;
        return $this;
    }

    /**
     * @return User|null
     */
    public function getUser(): ?User
    {
        return $this->user;
    }

    /**
     * @param User|null $user
     * @return $this
     */
    public function setUser(?User $user): self
    {
        $this->user = $user;
        return $this;
    }
}
