<?php

namespace App\Repository;

use App\Entity\Account;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Account>
 *
 * @method Account|null find($id, $lockMode = null, $lockVersion = null)
 * @method Account|null findOneBy(array $criteria, array $orderBy = null)
 * @method Account[]    findAll()
 * @method Account[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AccountRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Account::class);
    }

    /**
     * @param int $itemsPerPage
     * @param int $page
     * @param string|null $accountName
     * @param string|null $userName
     * @return float|int|mixed|string
     */
    public function getAllAccountByName(int $itemsPerPage, int $page, ?string $balance = null, ?string $userName = null)
    {
        return $this->createQueryBuilder("account")
            ->select( 'account.account_number', 'account.balance')

            //->andWhere("user.first_name LIKE :userName")

            //->setParameter("balance","%". $balance. "%")

            ->setFirstResult($itemsPerPage * ($page - 1))
            ->setMaxResults($itemsPerPage)
            ->orderBy('account.balance','ASC')
            ->getQuery()
            ->getResult();
    }
}
