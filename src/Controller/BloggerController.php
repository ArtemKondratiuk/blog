<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use App\Form\ArticleType;
use App\Services\ImageUploader;
use App\Entity\Article;
use App\Entity\Image;
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
    public function bloger(Request $request, ImageUploader $imageUploader)
    {
        $article = new Article();
        $image = new Image();

        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $files = $request->files->get('article')['images'];
            foreach ($files as $file) {
                $fileName = $imageUploader->upload($file);
                $image->setFileName($fileName);
                $image->setPath('/build/images/' . $fileName);
            }

            $article->setAuthor($this->getUser());
            $image->setArticle($article);
            $article->addImage($image);

            $em->persist($image);
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
