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

}
