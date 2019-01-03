<?php


namespace App\Services;

use App\Entity\Article;

class LikeServices
{
    public function countLikes(Article $article): int
    {
        $countLikes = $article->getUserLikes();
        $allLikes = count($countLikes);
        foreach($countLikes as $like){
            if($like->getLikes()==false){
                $countLikes--;
            }
        }

        return $allLikes;
    }

//    public function LikeOn(UserLike $like, Article $article, User $user, EntityManager $em)
//    {
//        if (!$like) {
//            $like = new UserLike();
//            $like
//                ->setArticle($article)
//                ->setUser($user)
//                ->setLikes(true);
//            $em->persist($like);
//            $em->flush();
//
//        } else {
//            $em->remove($like);
//            $em->flush();
//        }
//
//        return $like;
//    }


}
