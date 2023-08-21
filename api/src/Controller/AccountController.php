<?php

namespace App\Controller;

use App\Entity\Account;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Proxies\__CG__\App\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccountController extends AbstractController
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
     * @param Request $request
     * @return JsonResponse
     * @throws Exception
     */
    #[Route('account-create', name: 'account_create')]
    public function create(Request $request): JsonResponse
    {
        $requestData = json_decode($request->getContent(), true);
        $openDate = new \DateTime($requestData['open_date']);

        if (!isset(
            $requestData['balance'],
            $requestData['open_date'],
            $requestData['account_number'],
            $requestData['user']
        )) {
            throw new Exception("Invalid request data");
        }
        $user = $this->entityManager->getRepository(User::class)->find($requestData["user"]);
        $account = new Account();

        $account->setBalance($requestData['balance']);
        $account->setOpenDate($openDate);
        $account->setAccountNumber($requestData['account_number']);
        $account->setUser($user);

        if(!$user){
            throw new Exception("User with this id not found");
        }

        $this->entityManager->persist($account);

        $this->entityManager->flush();

        return new JsonResponse($account,Response::HTTP_CREATED);
    }

    /**
     * @return JsonResponse
     */
    #[Route('account-all', name: 'account_all')]
    public function getAll(): JsonResponse
    {
        $accounts = $this->entityManager->getRepository(Account::class)->findAll();

        return new JsonResponse($accounts);
    }

    /**
     * @param string $id
     * @return JsonResponse
     * @throws Exception
     */
    #[Route('account/{id}', name: 'account_get_item')]
    public function getItem(string $id): JsonResponse
    {
        $account = $this->entityManager->getRepository(Account::class)->find($id);

        if (!$account) {
            throw new Exception("Account with id " . $id . " not found");
        }

        return new JsonResponse($account);
   }

    /**
     * @param string $id
     * @return JsonResponse
     * @throws Exception
     */
   #[Route('account-update/{id}', name: 'account_update_item')]
   public function updateAccount(string $id): JsonResponse
    {
        /** @var Account $account */
        $account = $this->entityManager->getRepository(Account::class)->find($id);

        if (!$account) {
            throw new Exception("Account with id " . $id . " not found");
        }

        $account->setBalance(10000);
        $this->entityManager->flush();

        return new JsonResponse($account);
    }

    /**
     * @param string $id
     * @return JsonResponse
     * @throws Exception
     */
    #[Route('account-delete/{id}', name: 'account_delete_item')]
    public function deleteAccount(string $id): JsonResponse
   {
         /** @var Account $account */
        $account = $this->entityManager->getRepository(Account::class)->find($id);

        if (!$account) {
            throw new Exception("Account with id " . $id . " not found");
        }

        $this->entityManager->remove($account);

        $this->entityManager->flush();

        return new JsonResponse();
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    #[Route(path: "filter-accounts", name: "app_filter_accounts")]
    public function filterAccounts(Request $request): JsonResponse
    {
        $requestData = $request->query->all();

        $accounts = $this->entityManager->getRepository(Account::class)->getFilteredAccounts(
            $requestData['itemsPerPage'] ?? 10,
            $requestData['page'] ?? 1,
            $requestData['balance'] ?? null,
            $requestData['account_number'] ?? null,
            $requestData['open_date'] ?? null,
            $requestData['user_id'] ?? null
        );
        return new JsonResponse($accounts);
    }

}
