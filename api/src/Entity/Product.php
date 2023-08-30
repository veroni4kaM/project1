<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\RangeFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use App\Repository\ProductRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;
use App\Validator\Constraints\ProductConstraint;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
#[ProductConstraint]
#[ApiResource(collectionOperations: [
    "get" => [
        "method" => "GET",
        "normalization_context" => ["groups" => ["get:collection:product"]]
    ],
    "post" => [
        "method" => "POST",
        "denormalization_context" => ["groups" => ["post:collection:product"]],
        "normalization_context" => ["groups" => ["get:collection:product"]]
    ]
],
    itemOperations: [
        "get" => [
            "method" => "GET",
            "normalization_context" => ["groups" => ["get:item:product"]]
        ]
    ]
)]
#[ApiFilter(SearchFilter::class, properties: [
    "name"=>"partial",
    "description"
])]
#[ApiFilter(RangeFilter::class,properties: [
    "price"
])]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(["get:item:product"])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[NotBlank]
    #[NotNull]
    #[Groups(["get:collection:product", "get:item:product", "post:collection:product"])]
    private ?string $name = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 2, scale: '0')]
    #[Groups(["get:item:product", "post:collection:product", "get:collection:product"])]
    private ?string $price = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(["get:item:product", "post:collection:product", "get:collection:product"])]
    private ?string $description = null;

    /**
     * @var Category|null
     */
    #[ORM\ManyToOne(targetEntity: Category::class, inversedBy: "products")]
    #[Groups(["get:item:product", "post:collection:product"])]
    private ?Category $category = null;

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

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;
        return $this;
    }


}
      