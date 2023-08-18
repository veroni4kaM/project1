<?php

namespace App\Entity;

use App\Repository\AccountRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AccountRepository::class)]
class Account
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 15, scale: 2)]
    private ?string $balance = null;

    #[ORM\ManyToOne(targetEntity: User::class,inversedBy: "account")]
    private ?User $user = null;
    #[ORM\ManyToOne(targetEntity: Currency::class,inversedBy: "account")]
    private ?Currency $currency = null;


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
    public function setBalance(string $balance): static
    {
        $this->balance = $balance;

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
     * @return void
     */
    public function setUser(?User $user): void
    {
        $this->user = $user;
    }

    /**
     * @return Currency|null
     */
    public function getCurrency(): ?Currency
    {
        return $this->currency;
    }


    /**
     * @param Currency|null $currency
     * @return $this
     */
    public function setCurrency(?Currency $currency): self
    {
        $this->currency = $currency;
        return $this;
    }
}
