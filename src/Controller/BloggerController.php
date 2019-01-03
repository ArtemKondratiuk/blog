<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use App\Form\ArticleType;
use App\Entity\Article;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * @Route("/bloger" )
 * @Security("has_role('ROLE_BLOGER')")
 */
class BloggerController extends Controller
{
    /**
     * @Route("/", name="bloger")
     */
    public function bloger(Request $request)
    {
        $article = new Article();
        $article->setAuthor($this->getUser());
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($article);
            $em->flush();

            return $this->redirectToRoute('bloger');
        }

        return $this->render('bloger/bloger.html.twig', [
            'article' => $article,
            'form' => $form->createView(),
            ]);
    }
}