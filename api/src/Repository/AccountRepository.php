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
    public function getFilteredAccounts(int $itemsPerPage, int $page, ?string $balance = null, ?string $accountNumber = null, ?string $openDate = null)
    {
        return $this->createQueryBuilder("account")
            ->select( 'account.id','account.account_number', 'account.balance', 'account.open_date')

            ->andWhere('account.account_number LIKE :accountNumber')
            ->andWhere('account.balance LIKE :balance')
            ->andWhere('account.open_date LIKE :openDate')

            ->setParameter('accountNumber', '%' . $accountNumber . '%')
            ->setParameter('balance', '%' . $balance . '%')
            ->setParameter('openDate', '%' . $openDate . '%')

            ->setFirstResult($itemsPerPage * ($page - 1))
            ->setMaxResults($itemsPerPage)

            ->orderBy('account.open_date','DESC')
            ->getQuery()
            ->getResult();
    }
}
