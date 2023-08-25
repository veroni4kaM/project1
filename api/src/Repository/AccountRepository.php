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
    /**
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Account::class);
    }


    /**
     * @param int $itemsPerPage
     * @param int $page
     * @param string|null $balance
     * @param string|null $accountNumber
     * @param string|null $openDate
     * @param string|null $user
     * @return float|int|mixed|string
     */
    public function getFilteredAccounts(int $itemsPerPage, int $page, ?string $balance = null, ?string $accountNumber = null, ?string $openDate = null, ?string $user = null)
    {
        return $this->createQueryBuilder("account")
            ->select('account.id', 'account.account_number', 'account.balance', 'account.open_date')
            ->join("account.user","user")

            ->andWhere('account.account_number LIKE :accountNumber')
            ->andWhere('account.balance LIKE :balance')
            ->andWhere('account.open_date LIKE :openDate')
            //->andWhere('account.user LIKE :user')

                ->andWhere("account.user_id LIKE :user ")
            ->setParameter("user", "%". $user ."%")

            ->setParameter('accountNumber', '%' . $accountNumber . '%')
            ->setParameter('balance', '%' . $balance . '%')
            ->setParameter('openDate', '%' . $openDate . '%')
            //->setParameter('user', '%' . $user . '%')


            ->setFirstResult($itemsPerPage * ($page - 1))
            ->setMaxResults($itemsPerPage)

            ->orderBy('account.open_date', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
