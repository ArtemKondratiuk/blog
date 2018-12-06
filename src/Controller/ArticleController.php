<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Article;
use App\Form\CommentType;
use App\Repository\CommentRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;


class ArticleController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function listArticle(Request $request)
    {
                $articles = $this->getDoctrine()
            ->getRepository(Article::class)
            ->findAll();

        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $articles, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            5/*limit per page*/
        );

        return $this->render('base.html.twig', [
            'articles' => $articles,
            'pagination' => $pagination
        ]);

    }

    /**
     * @Route("/article/{id}", name="article")
     */
    public function showArticle(Request $request, Article $article, CommentRepository $commentRepository)
    {
        $comments = new Comment();
        $article->addComment($comments);
        $form = $this->createForm(CommentType::class, $comments);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
            $entityManager->persist($comments);
            $entityManager->flush();

            return $this->redirectToRoute('article', ['id' => $article->getId()]);
        }

        $comments = $commentRepository->findBy(['article' => $article]);

        return $this->render('article.html.twig', [
                'article' => $article,
                'comments' => $comments,
                'form' => $form->createView(),
            ]);
    }

}
