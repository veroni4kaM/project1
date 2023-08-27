<?php

namespace App\Entity;

use App\Repository\AccountRepository;
use DateTimeInterface;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;
use Symfony\Component\Validator\Constraints as Assert;
use App\Validator\Constraints\AccountConstraint;

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
     * @Assert\Regex(pattern="/^\d+(\.\d{1,2})?$/",message="Balance must be a valid decimal number with up to 2 decimal places."
     *  )
     */
    #[ORM\Column(type: Types::DECIMAL, precision: 15, scale: 2)]
    #[Assert\NotBlank]
    #[AccountConstraint]
    private ?string $balance = null;

    /**
     * @var DateTimeInterface|null
     * @Assert\NotBlank (message="Open date cannot be blank.")
     */
    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Assert\NotBlank]
    private ?DateTimeInterface $openDate = null;

    /**
     * @var string|null
     * @Assert\Length(
     *      min=1,
     *      max=16,
     *      minMessage="Account number must be at least {{ limit }} characters long.",
     *      maxMessage="Account number cannot be longer than {{ limit }} characters."
     *  )
     */
    #[ORM\Column(type: Types::DECIMAL, precision: 16, scale: '0')]
    #[Assert\NotBlank]
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

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;
        return $this;
    }
}
