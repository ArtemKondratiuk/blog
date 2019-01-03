<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use App\Form\ArticleType;
use Symfony\Component\HttpFoundation\File\UploadedFile;
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
    public function bloger(Request $request)
    {
        $article = new Article();
        $article->setAuthor($this->getUser());
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $files = $request->files->get('article')['images'];
            /** @var UploadedFile $file */

            foreach ($files as $file) {
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
                $em->persist($article);
                $em->flush();

                return $this->redirectToRoute('bloger');
            }

        }
        return $this->render('bloger/bloger.html.twig', [
            'article' => $article,
            'form' => $form->createView(),
        ]);
    }
}
