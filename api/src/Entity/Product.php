<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\ProductRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;
use App\Validator\Constraints\ProductConstraint;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
#[ProductConstraint]
#[ApiResource(collectionOperations: [
    "get"=>[
        "method"=>"GET",
        "security"=>"is_granted(' " . User::ROLE_USER . " ')"
    ]
],
    itemOperations: [
        "get"=>[
            "method"=>"GET"
        ]
    ],
    attributes: [
        "security"=>"is_granted(' " . User::ROLE_ADMIN . " ')"
    ]
)]
class Product implements JsonSerializable
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[NotBlank]
    #[NotNull]
    private ?string $name = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 2, scale: '0')]
    private ?string $price = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): void
    {
        $this->name = $name;
    }


    public function getPrice(): ?string
    {
        return $this->price;
    }

    public function setPrice(string $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function jsonSerialize()
    {
        return [

            "name" => $this->getName(),
            "price" => $this->getPrice(),
            "description" => $this->getDescription(),

        ];
    }

}
      