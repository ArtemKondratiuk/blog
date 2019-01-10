<?php

namespace App\Repository;

use App\Entity\UserLike;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;


class UserLikeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserLike::class);
    }

    public function allLike()
    {
        $qb = $this->createQueryBuilder('p')
            ->select('p.likes')
            ->from('App:UserLike', 'u')
        ;

        return $qb;
    }
}