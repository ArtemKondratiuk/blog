<?php

namespace App\Controller;


use App\Entity\Comment;
use App\Entity\Article;
use App\Repository\TagRepository;
use App\Entity\UserLike;
use App\Services\LikeServices;
use App\Form\CommentType;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;


class ArticleController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function listArticle(Request $request, PaginatorInterface $paginator, TagRepository $tags)
    {

        $tag = null;
        if ($request->query->has('tag')) {
            $tag = $tags->findOneBy(['name' => $request->query->get('tag')]);
        }

        $em = $this->getDoctrine()->getManager();
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
    public function showArticle(Request $request, Article $article, LikeServices $likes)
    {

        $comments = new Comment();

        $article->addComment($comments);
        $form = $this->createForm(CommentType::class, $comments);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $comments->setAuthor($this->getUser());
            $em = $this->getDoctrine()->getManager();
            $em->persist($comments);
            $em->flush();

            return $this->redirectToRoute('article', ['id' => $article->getId()]);
        }

        $em = $this->getDoctrine()->getManager();
        $comments = $em->getRepository(Comment::class)
            ->findBy(['article' => $article]);

        $allLike = $likes->countLikes($article);

//        $allLike = $em->getRepository(UserLikeRepository::class)
//            ->allLike();


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
    public function LikeAction(Article $article)
    {
        $user = $this->getUser();

        $em = $this->getDoctrine()->getManager();
        $like = $em->getRepository(UserLike::class)
            ->findOneBy(['user' => $user, 'article' => $article]);


        if (!$like) {
            $like = new UserLike();
            $like
                ->setArticle($article)
                ->setUser($user)
                ->setLikes(true);
            $em->persist($like);
            $em->flush();

        } else {
            $em->remove($like);
            $em->flush();
        }

        return $this->redirectToRoute('article', ['id' => $article->getId()]);

    }

}
