<?php

namespace App\Repository;

use App\Entity\Transaction;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Transaction>
 *
 * @method Transaction|null find($id, $lockMode = null, $lockVersion = null)
 * @method Transaction|null findOneBy(array $criteria, array $orderBy = null)
 * @method Transaction[]    findAll()
 * @method Transaction[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TransactionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Transaction::class);
    }

    /**
     * @param int $itemsPerPage
     * @param int $page
     * @param string|null $amount
     * @param string|null $transactionDate
     * @param string|null $isDeposit
     * @param string|null $accountId
     * @return float|int|mixed|string
     */
    public function getFilteredTransactions(
        int $itemsPerPage,
        int $page,
        ?string $amount = null,
        ?string $transactionDate = null,
        ?string $isDeposit = null,
        ?string $accountId = null
    ) {
        $queryBuilder = $this->createQueryBuilder('transaction')
            ->select(
                'transaction.id',
                'transaction.amount',
                'transaction.transaction_date',
                'transaction.is_deposit',
                'transaction.account_id'
            );

        if ($amount !== null) {
            $queryBuilder->andWhere($queryBuilder->expr()->like('transaction.amount', ':amount'))
                ->setParameter('amount', '%' . $amount . '%');
        }

        if ($transactionDate !== null) {
            $queryBuilder->andWhere($queryBuilder->expr()->like('transaction.transaction_date', ':transactionDate'))
                ->setParameter('transactionDate', '%' . $transactionDate . '%');
        }

        if ($isDeposit !== null) {
            $queryBuilder->andWhere($queryBuilder->expr()->eq('transaction.is_deposit', ':isDeposit'))
                ->setParameter('isDeposit', $isDeposit);
        }

        if ($accountId !== null) {
            $queryBuilder->andWhere($queryBuilder->expr()->like('transaction.account_id', ':accountId'))
                ->setParameter('accountId', '%' . $accountId . '%');
        }

        $queryBuilder->setFirstResult($itemsPerPage * ($page - 1))
            ->setMaxResults($itemsPerPage)
            ->orderBy('transaction.transaction_date', 'ASC');

        return $queryBuilder->getQuery()->getResult();
    }
}
