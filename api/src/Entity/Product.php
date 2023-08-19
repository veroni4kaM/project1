<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
class Product implements JsonSerializable
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 2, scale: '0')]
    private ?string $price = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\ManyToOne(targetEntity: Category::class,inversedBy: "product")]
    private ?Category $category = null;

/*    #[ORM\OneToOne(targetEntity: ProductInfo::class)]
    private ?ProductInfo $productInfo=null;*/
    #[ORM\ManyToMany(targetEntity: Test::class)]
    private Collection $test;
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
            "category"=>$this->getCategory()

        ];
    }

    public function getTest(): Collection
    {
        return $this->test;
    }

    public function setTest(Collection $test): void
    {
        $this->test = $test;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): void
    {
        $this->category = $category;
    }

/*    public function getProductInfo(): ?ProductInfo
    {
        return $this->productInfo;
    }

    public function setProductInfo(?ProductInfo $productInfo): void
    {
        $this->productInfo = $productInfo;
    }*/

}
      