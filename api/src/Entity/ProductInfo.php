<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
class ProductInfo implements JsonSerializable
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $info = null;
    public function getInfo(): ?string
    {
        return $this->info;
    }
    /*
     * @return $this;
     * */
    public function setInfo(?string $info): self
    {
        $this->info = $info;

        return $this;
    }

    public function jsonSerialize() : array
    {
        return [
            "id" => $this->getId(),
            "price" => $this->getInfo(),
        ];
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }
}
      