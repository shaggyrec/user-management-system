<?php

namespace App\Repository;

use App\Entity\AccessToken;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<AccessToken>
 *
 * @method AccessToken|null find($id, $lockMode = null, $lockVersion = null)
 * @method AccessToken|null findOneBy(array $criteria, array $orderBy = null)
 * @method AccessToken[]    findAll()
 * @method AccessToken[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AccessTokenRepository extends ServiceEntityRepository
{
    /**
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AccessToken::class);
    }

    /**
     * @param AccessToken $entity
     * @param bool $flush
     * @return void
     */
    public function save(AccessToken $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @param AccessToken $entity
     * @param bool $flush
     * @return void
     */
    public function remove(AccessToken $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @param string $token
     * @return AccessToken|null
     */
    public function findByToken(string $token): ?AccessToken
    {
        return $this->findOneBy(['token' => $token]);
    }

    /**
     * @param User $user
     * @return AccessToken|null
     */
    public function findByUser(User $user): ?AccessToken
    {
        return $this->findOneBy(['user' => $user]);
    }
}
