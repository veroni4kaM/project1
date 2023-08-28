<?php

namespace App\Controller;

use App\Entity\Account;
use App\Entity\User;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class UserController extends AbstractController
{

    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $entityManager;
    /**
     * @var Security
     */
    private Security $security;

    /**
     * @param EntityManagerInterface $entityManager
     * @param Security $security
     */
    public function __construct(EntityManagerInterface $entityManager, Security $security)
    {
        $this->entityManager = $entityManager;
        $this->security = $security;
    }
    /**
     * @param Request $request
     * @return JsonResponse
     * @throws Exception
     */
    #[IsGranted('ROLE_ADMIN')]
    #[Route('user-create', name: 'user_create')]
    public function create(Request $request): JsonResponse
    {
        $requestData = json_decode($request->getContent(), true);
        $registrationDate = new DateTime($requestData['registration_date']);
        $hasAccess = $this->isGranted('ROLE_ADMIN');

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
    #[IsGranted('IS_AUTHENTICATED_ANONYMOUSLY')]
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
        if ($this->security->isGranted('ROLE_ADMIN')) {
            $user = $this->entityManager->getRepository(User::class)->find($id);
            if (!$user) {
                throw new Exception("User with id " . $id . " not found");
            }
        }
        return new JsonResponse($user);

    }

    /**
     * @param string $id
     * @return JsonResponse
     * @throws Exception
     */
    #[IsGranted('ROLE_ADMIN')]
    #[Route('user-update/{id}', name: 'product_update_item')]
    public function updateUser(string $id, Request $request): JsonResponse
    {
        /** @var User $user */
        $user = $this->entityManager->getRepository(User::class)->find($id);

        if (!$user) {
            throw new Exception("User with id " . $id . " not found");
        }

        $requestData = json_decode($request->getContent(), true);
        if (isset($requestData['first_name'])) {
            $user->setFirstName($requestData['first_name']);
        }

        if (isset($requestData['last_name'])) {
            $user->setLastName($requestData['last_name']);
        }

        if (isset($requestData['email'])) {
            $user->setEmail($requestData['email']);
        }

        if (isset($requestData['registration_date'])) {
            $user->setRegistrationDate(new \DateTime($requestData['registration_date']));
        }
        $this->entityManager->flush();

        return new JsonResponse($user);
    }

    /**
     * @param string $id
     * @return JsonResponse
     * @throws Exception
     */
    #[Route('user-delete/{id}', name: 'user_delete_item')]
    #[IsGranted('ROLE_ADMIN')]
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
