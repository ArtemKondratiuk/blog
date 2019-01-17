<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Article;
use App\Entity\Tag;
use App\Entity\UserLike;
use App\Form\CommentType;
use App\Services\LikeServices;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ArticleController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function listArticleAction(Request $request, PaginatorInterface $paginator)
    {
        $em = $this->getDoctrine()->getManager();

        $tag = null;
        if ($request->query->has('tag')) {
            $tag = $em->getRepository(Tag::class)
                ->findOneBy(['name' => $request->query->get('tag')]);
        }

        $articles = $em->getRepository(Article::class)
            ->findLatest($tag);

        $pagination = $paginator->paginate(
            $articles, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            2/*limit per page*/
        );

        return $this->render('base.html.twig', [
            'pagination' => $pagination
        ]);
    }

    /**
     * @Route("/article/{id}", name="article")
     */
    public function showArticleAction(Request $request, Article $article)
    {
        $comments = new Comment();

        $form = $this->createForm(CommentType::class, $comments);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $article->addComment($comments);
            $comments->setAuthor($this->getUser());
            $em = $this->getDoctrine()->getManager();
            $em->persist($comments);
            $em->flush();

            return $this->redirectToRoute('article', ['id' => $article->getId()]);
        }

        $em = $this->getDoctrine()->getManager();
        $comments = $em->getRepository(Comment::class)
            ->findBy(['article' => $article]);

        $allLike = $em->getRepository(UserLike::class)
            ->allLike($article);

        return $this->render('article.html.twig', [
                'allLike' => $allLike,
                'article' => $article,
                'comments' => $comments,
                'form' => $form->createView(),
            ]);
    }

    /**
     * @Route("/article/{id}/like", name="like")
     */
    public function LikeAction(Article $article, LikeServices $likeServices)
    {
        $user = $this->getUser();

        $likeServices->LikesAction($article, $user);

        return $this->redirectToRoute('article', ['id' => $article->getId()]);
    }
}
