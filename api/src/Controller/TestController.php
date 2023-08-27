<?php

namespace App\Controller;

use App\Entity\Account;
use App\Entity\User;
use App\Entity\Users;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;



class TestController extends AbstractController
{

    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $entityManager;

    /**
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @return JsonResponse
     */
    #[Route(path: "test", name: "app_test")]
    public function test(): JsonResponse
    {
        $user = $this->getUser();

        $accounts = $this->entityManager->getRepository(Account::class)->findAll();

        if (in_array(User::ROLE_ADMIN, $user->getRoles())) {
            return new JsonResponse($accounts);
        }

        return new JsonResponse($this->fetchAccountsForUser($accounts));
    }
    public function fetchAccountsForUser(array $accounts) : array
    {
        $fetchedAccountsForUser = null;
        /** @var Account $account */
        foreach ($accounts as $account) {
            $tmpAccountData = $account->jsonSerialize();


            $fetchedAccountsForUser[] = $tmpAccountData;
        }
        return $fetchedAccountsForUser;
    }

}