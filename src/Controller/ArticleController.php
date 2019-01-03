<?php

namespace App\Controller;

use App\Entity\Article;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ArticleController extends AbstractController
{
    /**
     * @Route("/", name="homepage")
     */
    public function listArticle()
    {
        $articles = $this->getDoctrine()
            ->getRepository(Article::class)
            ->findAll();


        return $this->render('base.html.twig', [
            'articles' => $articles
        ]);

    }
}
