<?php

namespace App\Controller;

use App\Entity\Account;
use App\Entity\Transaction;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints as Assert;

class TransactionController extends AbstractController
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
     * @throws Exception
     */
    #[IsGranted('ROLE_USER')]
    #[Route('transaction-create', name: 'transaction_create')]
    public function create(Request $request): JsonResponse
    {
        $requestData = json_decode($request->getContent(), true);

        if (!isset(
            $requestData['amount'],
            $requestData['transaction_date'],
            $requestData['is_deposit']
        )) {
            throw new Exception("Invalid request data");
        }
        $account = $this->entityManager->getRepository(Account::class)->find($requestData["account"]);

        $transaction = new Transaction();

        $transaction->setAmount($requestData['amount']);
        $transaction->setTransactionDate(new DateTime($requestData['transaction_date']));
        $transaction->setIsDeposit($requestData['is_deposit']);

            $this->entityManager->persist($transaction);
            $this->entityManager->flush();

        if ($this->isTransactionAccessibleByUser($transaction)) {
            return new JsonResponse($transaction, Response::HTTP_CREATED);
        } else {
            throw new Exception("Access denied.");
        }
    }

    /**
     * @return JsonResponse
     */
    #[IsGranted('IS_AUTHENTICATED_ANONYMOUSLY')]
    #[Route('transaction-all', name: 'transaction_all')]
    public function getAll(): JsonResponse
    {
        $transactions = $this->entityManager->getRepository(Transaction::class)->findAll();

        return new JsonResponse($transactions);
    }

    /**
     * @throws Exception
     */
    #[Route('transaction/{id}', name: 'transaction_get_item')]
    #[IsGranted('ROLE_USER')]
    public function getItem(string $id): JsonResponse
    {
        $transaction = $this->entityManager->getRepository(Transaction::class)->find($id);

        if (!$transaction) {
            throw new Exception("Transaction with id " . $id . " not found");
        }
        if ($this->isTransactionAccessibleByUser($transaction)) {
            return new JsonResponse($transaction);
        } else {
            throw new Exception("Access denied.");
        }
    }

    /**
     * @throws Exception
     */
    #[Route('transaction-update/{id}', name: 'transaction_update_item')]
    #[IsGranted('ROLE_USER')]
    public function updateTransaction(string $id, Request $request): JsonResponse
    {
        /** @var Transaction $transaction */

        $transaction = $this->entityManager->getRepository(Transaction::class)->find($id);

        if (!$transaction) {
            throw new Exception("Transaction with id " . $id . " not found");
        }
        $requestData = json_decode($request->getContent(), true);

        if (isset($requestData['amount'])) {
            $transaction->setAmount($requestData['amount']);
        }

        if (isset($requestData['transaction_date'])) {
            $transaction->setTransactionDate(new \DateTime($requestData['transaction_date']));
        }

        if (isset($requestData['is_deposit'])) {
            $transaction->setIsDeposit($requestData['is_deposit']);
        }
        $this->entityManager->flush();


        if ($this->isTransactionAccessibleByUser($transaction)) {
            return new JsonResponse($transaction);
        } else {
            throw new Exception("Access denied.");
        }
    }

    #[Route('transaction-delete/{id}', name: 'transaction_delete_item')]
    #[IsGranted('ROLE_USER')]
    public function deleteTransaction(string $id): JsonResponse
    {
        /** @var Transaction $transaction */
        $transaction = $this->entityManager->getRepository(Transaction::class)->find($id);

        if (!$transaction) {
            throw new Exception("Transaction with id " . $id . " not found");
        }
        // Перевірка, чи поточний користувач є власником транзакції
            $this->entityManager->remove($transaction);
            $this->entityManager->flush();

        if ($this->isTransactionAccessibleByUser($transaction)) {
            return new JsonResponse();
        } else {
            throw new Exception("Access denied.");
        }
    }

    #[Route(path: "filter-transactions", name: "app_filter_transactions")]
    #[IsGranted('ROLE_USER', 'ROLE_ADMIN')]
    public function filterTransactions(Request $request): JsonResponse
    {
        $requestData = $request->query->all();

        $transactions = $this->entityManager->getRepository(Transaction::class)->getFilteredTransactions(
            $requestData['itemsPerPage'] ?? 10,
            $requestData['page'] ?? 1,
            $requestData['amount'] ?? null,
            $requestData['transaction_date'] ?? null,
            $requestData['is_deposit'] ?? null
        );

        return new JsonResponse($transactions);
    }
    // Перевірка, чи користувач має доступ до цієї транзакції
    private function isTransactionAccessibleByUser(Transaction $transaction): bool
    {
       // return ($this->getUser() === $transaction->getAccount()->getUser());
    }

}
