<?php

namespace App\Controller;

use App\Entity\Group;
use App\Repository\GroupRepository;
use App\Repository\UserRepository;
use App\Request\UserCreateRequest;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/groups')]
class GroupController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    private GroupRepository $groupRepository;

    private UserRepository $userRepository;

    /**
     * @param EntityManagerInterface $entityManager
     * @param GroupRepository $groupRepository
     * @param UserRepository $userRepository
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        GroupRepository $groupRepository,
        UserRepository $userRepository
    ) {
        $this->entityManager = $entityManager;
        $this->groupRepository = $groupRepository;
        $this->userRepository = $userRepository;
    }
    #[Route(path: '', name: 'groups_list', methods: ['GET'])]
    public function getList(): JsonResponse
    {
        return $this->json($this->groupRepository->findAll());
    }

    #[Route(path: '/{id}', name: 'group_by_id', methods: ['GET'])]
    public function getGroupById(int $id): JsonResponse
    {
        $group = $this->groupRepository->find($id);

        if (!$group) {
            throw new NotFoundHttpException('Group not found');
        }

        return $this->json($group);
    }

    #[Route(path: '', name: 'group_add', methods: 'POST')]
    public function addGroup(UserCreateRequest $request): JsonResponse
    {
        $group = (new Group())->setName($request->name);

        $this->entityManager->persist($group);
        $this->entityManager->flush();

        return $this->json($group);
    }

    #[Route(path: '/{id}', name: 'group_delete', methods: 'DELETE')]
    public function deleteGroup(int $id): JsonResponse
    {
        $group = $this->groupRepository->find($id);

        if (!$group) {
            throw new NotFoundHttpException('Group not found');
        }

        if ($group->getUsers()->count() > 0) {
            throw new BadRequestException('Group is not empty');
        }

        $this->entityManager->remove($group);
        $this->entityManager->flush();

        return new JsonResponse(['status' => 'Group deleted'], Response::HTTP_OK);
    }

    #[Route(path: '/{groupId}/users/{userId}', methods: ['PUT'])]
    public function addUserToGroup(int $groupId, int $userId): Response
    {
        $group = $this->groupRepository->find($groupId);
        $user = $this->userRepository->find($userId);

        if (!$group || !$user) {
            throw new NotFoundHttpException('Group or user not found');
        }

        if ($group->getUsers()->contains($user)) {
            throw new BadRequestException('User is already in this group');
        }

        $group->addUser($user);

        $this->entityManager->persist($group);
        $this->entityManager->flush();

        return new JsonResponse(['status' => 'User added to group'], Response::HTTP_OK);
    }

    #[Route(path: '/{groupId}/users/{userId}', methods: ['DELETE'])]
    public function removeUserFromGroup(int $groupId, int $userId): Response
    {
        $group = $this->groupRepository->find($groupId);
        $user = $this->userRepository->find($userId);

        if (!$group || !$user) {
            throw new NotFoundHttpException('Group or user not found');
        }

        if ($group->getUsers()->contains($user)) {
            $group->removeUser($user);

            $this->entityManager->persist($group);
            $this->entityManager->flush();
        }

        return new JsonResponse(['status' => 'User removed from group'], Response::HTTP_OK);
    }
}
