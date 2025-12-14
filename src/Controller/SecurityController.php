<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class SecurityController extends AbstractController
{
    #[Route('/', name: 'security_login')]
    public function login(): Response
    {
        return $this->render('security/login.html.twig', [
            #'controller_name' => 'SecurityController',
            'last_username' => '', 
            'error'         => null,
        ]);
    }

    #[Route('/logout', name: 'app_logout')]
    public function logout(): Response
    {
        return $this->redirectToRoute('security_login');
    }
}
