<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserRepository::class)]
class User
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
    private ?string $first_name = null;

    /**
     * @var string|null
     */
    #[ORM\Column(length: 255)]
    private ?string $last_name = null;

    /**
     * @var string|null
     */
    #[ORM\Column(length: 255)]
    private ?string $email = null;

    /**
     * @var string|null
     */
    #[ORM\Column(length: 255)]
    private ?string $password = null;

    /**
     * @var \DateTimeInterface|null
     */
    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $registration_date = null;

    /**
     * @var Collection
     */
    #[ORM\OneToMany(mappedBy: "user", targetEntity: Account::class)]
    private Collection $accounts;
    /**
     * @var Collection
     */
    #[ORM\OneToMany(mappedBy: "user", targetEntity: Recipient::class)]
    private Collection $recipients;

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
    public function getFirstName(): ?string
    {
        return $this->first_name;
    }

    /**
     * @param string $first_name
     * @return $this
     */
    public function setFirstName(string $first_name): static
    {
        $this->first_name = $first_name;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getLastName(): ?string
    {
        return $this->last_name;
    }

    /**
     * @param string $last_name
     * @return $this
     */
    public function setLastName(string $last_name): static
    {
        $this->last_name = $last_name;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @param string $email
     * @return $this
     */
    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    /**
     * @param string $password
     * @return $this
     */
    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @return \DateTimeInterface|null
     */
    public function getRegistrationDate(): ?\DateTimeInterface
    {
        return $this->registration_date;
    }

    /**
     * @param \DateTimeInterface $registration_date
     * @return $this
     */
    public function setRegistrationDate(\DateTimeInterface $registration_date): static
    {
        $this->registration_date = $registration_date;

        return $this;
    }

    /**
     * @return Collection
     */
    public function getAccounts(): Collection
    {
        return $this->accounts;
    }

    /**
     * @param Collection $accounts
     * @return void
     */
    public function setAccounts(Collection $accounts): void
    {
        $this->accounts = $accounts;
    }

    /**
     * @return Collection
     */
    public function getRecipients(): Collection
    {
        return $this->recipients;
    }

    /**
     * @param Collection $recipients
     * @return $this
     */
    public function setRecipients(Collection $recipients): self
    {
        $this->recipients = $recipients;
        return $this;
    }
}
