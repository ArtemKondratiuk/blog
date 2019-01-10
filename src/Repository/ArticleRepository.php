<?php

namespace App\Repository;

use App\Entity\Article;
use App\Entity\Tag;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Article|null find($id, $lockMode = null, $lockVersion = null)
 * @method Article|null findOneBy(array $criteria, array $orderBy = null)
 * @method Article[]    findAll()
 * @method Article[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArticleRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Article::class);
    }

    public function findLatest(Tag $tag = null)
    {
          $qb = $this->createQueryBuilder('p')
             ->addSelect('a', 't')
             ->innerJoin('p.author', 'a')
             ->leftJoin('p.tags', 't')
             ->where('p.publishedAt <= :now', 'p.publish = true')
             ->orderBy('p.publishedAt', 'DESC')
             ->setParameter('now', new \DateTime());

        if (null !== $tag) {
            $qb->andWhere(':tag MEMBER OF p.tags')
                ->setParameter('tag', $tag);
        }

        return $qb;
    }

}
