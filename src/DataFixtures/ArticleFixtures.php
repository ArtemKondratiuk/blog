<?php


namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Article;
use App\Entity\Comment;
use App\Entity\Tag;

class ArticleFixtures extends Fixture
{
    public function load(ObjectManager $manager)

    {

        for($i=0; $i<20; $i++) {
            $article =new Article();
            $article->setTitle('some title'.$i);
            $article->setText('some article'.$i);
//            $article->setPublishedAt('2018-11-28');
//            $article->setAuthor('Artem');
            $tag = new Tag();
            $tag->setName('some tag'.$i);
            $manager->persist($tag);
            $article->addTag($tag);


            $manager->persist($article);

            $comment = new Comment();
            $comment->setText('some comment'.$i);
//            $comment->setPublishedAt('2018-11-28');
//            $comment->setAuthor('Artem');
            $comment->setArticle($article);
            $manager->persist($comment);
        }
        $manager->flush();

    }



}

