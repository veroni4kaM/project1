<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

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
    /**
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * @param int $itemsPerPage
     * @param int $page
     * @param string|null $firstName
     * @param string|null $lastName
     * @param string|null $email
     * @param string|null $registrationDate
     * @return float|int|mixed|string
     */
    public function getFilteredUsers(int $itemsPerPage, int $page, ?string $firstName = null, ?string $lastName = null, ?string $email = null, ?string $registrationDate = null): mixed
    {
        return $this->createQueryBuilder("user")
            ->select('user.id', 'user.first_name', 'user.last_name', 'user.email', 'user.registration_date')

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

            ->orderBy('user.registration_date', 'ASC')
            ->getQuery()
            ->getResult();
    }
    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', $user::class));
        }

        $user->setPassword($newHashedPassword);
        $this->getEntityManager()->persist($user);
        $this->getEntityManager()->flush();
    }
}
