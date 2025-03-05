<?php

namespace App\Repository;

use App\Entity\MicroPost;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<MicroPost>
 */
class MicroPostRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MicroPost::class);
    }

    public function save(MicroPost $microPost, bool $flush = true): void
    {
        $this->getEntityManager()->persist($microPost);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(MicroPost $microPost, bool $flush = true): void
    {
        $this->getEntityManager()->remove($microPost);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findLatest(int $limit = 10): array
    {
        return $this->createQueryBuilder('m')
            ->orderBy('m.created', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

}
