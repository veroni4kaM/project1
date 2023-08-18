<?php

namespace App\Entity;

use App\Repository\ExchangeRateRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ExchangeRateRepository::class)]
class ExchangeRate
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 6)]
    private ?string $exchange_rate = null;

    #[ORM\ManyToOne(targetEntity: Currency::class)]
    #[ORM\JoinColumn(name: "base_currency_id",referencedColumnName: "id")]
    private $baseCurrency;
    #[ORM\ManyToOne(targetEntity: Currency::class)]
    #[ORM\JoinColumn(name: "converted_currency_id",referencedColumnName: "id")]
    private $convertedCurrency;


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
    public function getExchangeRate(): ?string
    {
        return $this->exchange_rate;
    }

    /**
     * @param string $exchange_rate
     * @return $this
     */
    public function setExchangeRate(string $exchange_rate): static
    {
        $this->exchange_rate = $exchange_rate;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getBaseCurrency()
    {
        return $this->baseCurrency;
    }

    /**
     * @param mixed $baseCurrency
     */
    public function setBaseCurrency($baseCurrency): self
    {
        $this->baseCurrency = $baseCurrency;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getConvertedCurrency()
    {
        return $this->convertedCurrency;
    }

    /**
     * @param mixed $convertedCurrency
     */
    public function setConvertedCurrency($convertedCurrency): self
    {
        $this->convertedCurrency = $convertedCurrency;
        return $this;
    }
}
