<?php

namespace App\Controller\Api\Article;

use App\Entity\Article;
use App\Exception\JsonHttpException;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @Route("api")
 */
class ArticleController extends Controller
{
    /**
     * @Rest\Get("/articles")
     */
    public function listArticleAction()
    {
        $articles = $this->getDoctrine()->getRepository('App:Article')->findAll();
        if ($articles === null) {
            return $this->json("there are no article exist", Response::HTTP_NOT_FOUND);
        }
        return  $this->json($articles);
    }

    /**
     * @Rest\Get("/article/{id}")
     */
    public function showArticleAction($id)
    {
        $article = $this->getDoctrine()->getRepository('App:Article')->find($id);
        if ($article === null) {
            return $this->json("user not found", Response::HTTP_NOT_FOUND);
        }
        return $this->json($article);
    }

    /**
     * @Rest\Post("/article/add")
     */
    public function addArticleAction(Request $request, SerializerInterface $serializer, ValidatorInterface $validator)
    {
        if (!$content = $request->getContent()) {
            throw new JsonHttpException(400, 'Bad Request');
        }
        /** @var Article $article */
        $article = $serializer->deserialize($request->getContent(), Article::class, 'json');
        $errors = $validator->validate($article);
        if (count($errors)) {
            throw new JsonHttpException(400, 'Bad Request');
        }
        $this->getDoctrine()->getManager()->persist($article);
        $this->getDoctrine()->getManager()->flush();
        return $this->json($article);
    }
}
