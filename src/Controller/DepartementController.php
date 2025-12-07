<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class DepartementController extends AbstractController
{
    public function __construct()
    {
        throw new \Exception('Not implemented');
    }
    #[Route('/departement', name: 'app_departement', methods: ['GET'])]
    public function index(): Response
    {
        $departements =$this->repo->findAll();
        dd($departements);
            
        return $this->render('departement/index.html.twig', [
            'controller_name' => 'DepartementController',
        ]);
    }
}
