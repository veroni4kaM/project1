<?php

namespace App\Controller;

use App\Entity\Account;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
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
    #[Route('user-create', name: 'user_create')]
    public function create(Request $request): JsonResponse
    {
        $requestData = json_decode($request->getContent(), true);
        $registrationDate = new \DateTime($requestData['registration_date']);

        if (!isset(
            $requestData['first_name'],
            $requestData['last_name'],
            $requestData['email'],
            $requestData['password'],
            $requestData['registration_date']
        )) {
            throw new Exception("Invalid request data");
        }

        $user = new User();

        $user->setFirstName($requestData['first_name']);
        $user->setLastName($requestData['last_name']);
        $user->setEmail($requestData['email']);
        $user->setPassword($requestData['password']);
        $user->setRegistrationDate($registrationDate);

        $this->entityManager->persist($user);

        $this->entityManager->flush();

        return new JsonResponse($user, Response::HTTP_CREATED);
    }

    /**
     * @return JsonResponse
     */
    #[Route('user-all', name: 'user_all')]
    public function getAll(): JsonResponse
    {
        $users = $this->entityManager->getRepository(User::class)->findAll();

        return new JsonResponse($users);
    }

    /**
     * @param string $id
     * @return JsonResponse
     * @throws Exception
     */
    #[Route('user/{id}', name: 'user_get_item')]
    public function getItem(string $id): JsonResponse
    {
        $user = $this->entityManager->getRepository(User::class)->find($id);

        if (!$user) {
            throw new Exception("User with id " . $id . " not found");
        }

        return new JsonResponse($user);
    }

    /**
     * @param string $id
     * @return JsonResponse
     * @throws Exception
     */
    #[Route('user-update/{id}', name: 'product_update_item')]
    public function updateUser(string $id): JsonResponse
    {
        /** @var User $user */
        $user = $this->entityManager->getRepository(User::class)->find($id);

        if (!$user) {
            throw new Exception("User with id " . $id . " not found");
        }

        $user->setFirstName("Анатолій");

        $this->entityManager->flush();

        return new JsonResponse($user);
    }

    /**
     * @param string $id
     * @return JsonResponse
     * @throws Exception
     */
    #[Route('user-delete/{id}', name: 'user_delete_item')]
    public function deleteUser(string $id): JsonResponse
    {
        /** @var User $user */
        $user = $this->entityManager->getRepository(User::class)->find($id);

        if (!$user) {
            throw new Exception("User with id " . $id . " not found");
        }

        $this->entityManager->remove($user);

        $this->entityManager->flush();

        return new JsonResponse();
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    #[Route(path: "filter-users", name: "app_filter_users")]
    public function filterUsers(Request $request): JsonResponse
    {
        $requestData = $request->query->all();

        $accounts = $this->entityManager->getRepository(User::class)->getFilteredUsers(
            $requestData['itemsPerPage'] ?? 10,
            $requestData['page'] ?? 1,
            $requestData['first_name'] ?? null,
            $requestData['last_name'] ?? null,
            $requestData['email'] ?? null,
            $requestData['registration_date'] ?? null
        );
        return new JsonResponse($accounts);
    }

}
