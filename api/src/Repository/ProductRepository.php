<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Product>
 *
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    /**
     * @param string $name
     * @return float|int|mixed|string
     */
    public function getAllProductByName(int $itemsPerPage, int $page, ?string $categoryName = null, ?string $name = null)
    {
        return $this->createQueryBuilder("product")
            ->select('product.name')
            ->join('product.category','category')

            ->andWhere('category.name LIKE :categoryName')
            ->andWhere("product.name LIKE :name")

            ->setParameter("name","%". $name. "%")
            ->setParameter("categoryName","%". $categoryName. "%")

            ->setFirstResult($itemsPerPage * ($page - 1))
            ->setMaxResults($itemsPerPage)
            ->orderBy('product.name','ASC')
            ->getQuery()
            ->getResult();
    }
}
