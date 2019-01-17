<?php

namespace App\Repository;

use App\Entity\UserLike;
use App\Entity\Article;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

class UserLikeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserLike::class);
    }

    public function allLike(Article $article)
    {
        return $this->createQueryBuilder('ul')
            ->andWhere('ul.article = :article')
            ->setParameter('article', $article)
            ->select('COUNT(ul)')
            ->getQuery()
            ->getSingleScalarResult();
    }
}
