<?php

namespace App\EntityListener;

use App\Entity\AccessToken;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Event\PostPersistEventArgs;
use Doctrine\ORM\Events;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;

#[AsEntityListener(event: Events::postPersist, method: 'postPersist', entity: User::class)]
class UserEntityListener
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager
    ) {
    }

    /**
     * @param User $user
     * @param PostPersistEventArgs $event
     * @return void
     */
    public function postPersist(User $user, PostPersistEventArgs $event): void
    {
        $this->entityManager->persist(
            (new AccessToken())
                ->setUser($user)
        );
        $this->entityManager->flush();
    }
}
