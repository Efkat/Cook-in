<?php

namespace App\Controller\Auth;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class LoginController extends AbstractController
{
    #[Route('/login', name: 'app_login')]
    public function index(AuthenticationUtils $authenticationUtils): Response
    {
        if($this->getUser()){
            return ($this->redirect($this->generateUrl('app_index')));
        }

        return $this->render('auth/login.html.twig', [
            "error" => $authenticationUtils->getLastAuthenticationError(),
            "last_username" => $authenticationUtils->getLastUsername(),
            'title' => "Coonexion"
        ]);
    }
}
