<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<User>
 *
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function getFilteredUsers(int $itemsPerPage, int $page, ?string $firstName = null, ?string $lastName = null,?string $email = null, ?string $registrationDate = null)
    {
        return $this->createQueryBuilder("user")
            ->select( 'user.id','user.first_name', 'user.last_name', 'user.email','user.registration_date')

            ->andWhere('user.first_name LIKE :firstName')
            ->andWhere('user.last_name LIKE :lastName')
            ->andWhere('user.email LIKE :email')
            ->andWhere('user.registration_date LIKE :registrationDate')

            ->setParameter('firstName', '%' . $firstName . '%')
            ->setParameter('lastName', '%' . $lastName . '%')
            ->setParameter('email', '%' . $email . '%')
            ->setParameter('registrationDate', '%' . $registrationDate . '%')

            ->setFirstResult($itemsPerPage * ($page - 1))
            ->setMaxResults($itemsPerPage)

            ->orderBy('user.registration_date','DESC')
            ->getQuery()
            ->getResult();
    }
}
