<?php

namespace App\Controller;

use App\Entity\Departement; 
use App\Form\DepartementType; 
use Doctrine\ORM\EntityManagerInterface; 
use App\Repository\DepartementRepository;
use App\Repository\EmployeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request; 
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Knp\Component\Pager\PaginatorInterface;

class DepartementController extends AbstractController
{
    #[Route('/departements/list', name: 'app_departement_list', methods: ['GET', 'POST'])]
    public function index(DepartementRepository $departementRepository, EmployeRepository $employeRepository, Request $request, PaginatorInterface $paginator, EntityManagerInterface $entityManager): Response
    {
        $departement = new Departement();
        
        $form = $this->createForm(DepartementType::class, $departement, [
            'action' => $this->generateUrl('app_departement_list'), 
            'method' => 'POST',
        ]);
        
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $departement->setIsActive(true); 
            $entityManager->persist($departement);
            $entityManager->flush();
            
            $this->addFlash('success', 'Le département a été enregistré avec succès !');
            
            return $this->redirectToRoute('app_departement_list');
        }


        $queryBuilder = $departementRepository->createQueryBuilder('d')->getQuery();
        
        $pagination = $paginator->paginate($queryBuilder, $request->query->getInt('page', 1), 8);

        $employeCounts = [];
        /** @var Departement $departementItem */
        foreach ($pagination->getItems() as $departementItem) {
            $employeCounts[$departementItem->getId()] = $employeRepository->count(['departement' => $departementItem]);
        }


        return $this->render('departement/index.html.twig', [
            'departements' => $pagination, // L'objet de pagination est passé à la vue
            'employeCounts' => $employeCounts,
            'creationForm' => $form->createView(),
        ]);
    }
}