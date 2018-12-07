<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\LoginType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="app_login")
     */
    public function loginAction(AuthenticationUtils $authenticationUtils)
    {
        $user = new User();
        $user->setEmail($authenticationUtils->getLastUsername());

        $form = $this->createForm(LoginType::class, $user, [
            'action' => $this->generateUrl('login_check')
        ]);

        if ($error = $authenticationUtils->getLastAuthenticationError()) {
            $this->addFlash('message', $error->getMessage());
        }

        return $this->render('login.html.twig', [
            'form' => $form->createView(),
        ]);
    }

}
