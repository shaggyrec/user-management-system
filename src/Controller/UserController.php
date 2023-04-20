<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\AccessTokenRepository;
use App\Repository\UserRepository;
use App\Request\UserCreateRequest;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class UserController
 *
 * @package App\Controller
 */
#[Route('/users')]
class UserController extends AbstractController
{
    private Security $security;

    private UserRepository $userRepository;
    private EntityManagerInterface $entityManager;

    private AccessTokenRepository $accessTokenRepository;

    /**
     * @param UserRepository $userRepository
     * @param EntityManagerInterface $entityManager
     * @param AccessTokenRepository $accessTokenRepository
     * @param Security $security
     */
    public function __construct(
        UserRepository $userRepository,
        EntityManagerInterface $entityManager,
        AccessTokenRepository $accessTokenRepository,
        Security $security
    ) {
        $this->userRepository = $userRepository;
        $this->entityManager = $entityManager;
        $this->accessTokenRepository = $accessTokenRepository;
        $this->security = $security;
    }

    #[Route('', name: 'user_list', methods: 'GET')]
    public function getList(): JsonResponse
    {
        return $this->json($this->userRepository->findAll());
    }

    #[Route('/{id}', name: 'user_get', methods: 'GET')]
    public function getOneUser(int $id): JsonResponse
    {
        return $this->json($this->userRepository->find($id));
    }

    #[Route('', name: 'user_add', methods: 'POST')]
    public function addUser(UserCreateRequest $request): JsonResponse
    {
        $user = (new User())->setName($request->name);

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $this->json(
            [
                'user' => $user,
                'token' => $this->accessTokenRepository->findByUser($user)->getToken(),
            ],
        );
    }

    #[Route('/{id}', name: 'user_delete', methods: 'DELETE')]
    public function deleteUser(int $id): JsonResponse
    {
        $user = $this->userRepository->find($id);

        if (!$user) {
            throw new NotFoundHttpException('User not found');
        }

        if ($this->security->getUser()->getId() === $user->getId()) {
            throw new NotFoundHttpException('User must not make a suicide');
        }

        $this->entityManager->remove($user);
        $this->entityManager->flush();

        return new JsonResponse(['status' => 'User deleted'], Response::HTTP_OK);
    }
}
