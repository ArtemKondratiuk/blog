<?php


namespace App\Services;

use App\Entity\Article;
use App\Entity\User;
use App\Entity\UserLike;
use Doctrine\Common\Persistence\ManagerRegistry;

class LikeServices
{
    private $em;

    public function __construct(ManagerRegistry $em)
    {
        $this->em = $em;
    }

    public function LikesAction(Article $article, User $user)
    {
        $like = $this->em->getRepository(UserLike::class)
            ->findOneBy(['user' => $user, 'article' => $article]);

        if (!$like) {
            $like = new UserLike();
            $like
                ->setArticle($article)
                ->setUser($user)
                ->setLikes(true);
            $this->em->getManager()->persist($like);
            $this->em->getManager()->flush();
        } else {
            $this->em->getManager()->remove($like);
            $this->em->getManager()->flush();
        }

        return $like;
    }
}
