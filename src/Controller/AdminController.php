<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Image;
use App\Entity\User;
use App\Form\ArticleType;
use App\Form\UserEditType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;

/**
 * @Route("/admin")
 */
class AdminController extends AbstractController
{

    /**
     * @Route("/", name="dashboard", methods="GET")
     */
    public function dashboard()
    {
        return $this->render('admin/dashboard.html.twig');
    }

    /**
     * @Route("/all-articles", name="article_index", methods="GET")
     */
    public function articleManager(Request $request, PaginatorInterface $paginator): Response
    {

        $em = $this->getDoctrine()->getManager();
        $articles = $em->getRepository(Article::class)
            ->findLatest();

        $pagination = $paginator->paginate(
            $articles, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            10/*limit per page*/
        );

        return $this->render('admin/article.html.twig', [
            'pagination' => $pagination
        ]);
    }

    /**
     * @Route("/all-user", name="user_index", methods="GET")
     */
    public function userManager(Request $request, PaginatorInterface $paginator): Response
    {

        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository(User::class)
            ->findAll();

        $pagination = $paginator->paginate(
            $user, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            10/*limit per page*/
        );

        return $this->render('admin/user.html.twig', [
            'pagination' => $pagination,
        ]);
    }

    /**
     * @Route("/user-edit/{id}", name="user-edit")
     */
    public function editUser(Request $request, User $user)
    {

        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(UserEditType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($user);
            $em->flush();
        }

        return $this->render('admin/user_edit.html.twig', [
            'user' => $user,
            'form' => $form->createView()
        ]);
    }


    /**
     * @Route("/new", name="article_new", methods="GET|POST")
     */
    public function new(Request $request): Response
    {
        $article = new Article();
        $article->setAuthor($this->getUser());
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $files = $request->files->get('article')['images'];
            /** @var UploadedFile $file */

            foreach ($files as $file){
                $image = new Image();

                $fileName = md5(uniqid()) . $file->guessExtension();
                $image->setFileName($fileName);

                $image->setPath(
                    '/build/images/' . $fileName
                );

                $file->move(
                    $this->getParameter('image_directory'),
                    $fileName
                );

                $image->setArticle($article);
                $article->addImage($image);

                $em->persist($image);
            }
            $em->persist($article);
            $em->flush();

            return $this->redirectToRoute('article_index');
        }

        return $this->render('admin/new.html.twig', [
            'article' => $article,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="article_show", methods="GET")
     */
    public function show(Article $article): Response
    {
        return $this->render('admin/show.html.twig', ['article' => $article]);
    }

    /**
     * @Route("/{id}/edit", name="article_edit", methods="GET|POST")
     */
    public function edit(Request $request, Article $article): Response
    {
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('article_index', ['id' => $article->getId()]);
        }

        return $this->render('admin/edit.html.twig', [
            'article' => $article,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="article_delete", methods="DELETE")
     */
    public function delete(Request $request, Article $article): Response
    {
        if ($this->isCsrfTokenValid('delete'.$article->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($article);
            $em->flush();
        }

        return $this->redirectToRoute('article_index');
    }

}
