<?php

namespace App\Controller;

use App\Entity\Departement;
use App\Repository\EmployeRepository; 
use App\Repository\DepartementRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request; 
use Knp\Component\Pager\PaginatorInterface;

final class EmployeController extends AbstractController
{
    #Liste des employes
    #[Route('/employes/list', name: 'app_employe_list', methods: ['GET'])]
    public function index(EmployeRepository $employeRepository, Request $request, PaginatorInterface $paginator): Response
    {
        $queryBuilder = $employeRepository->createQueryBuilder('e')->getQuery();
        
        $employesPagination = $paginator->paginate($queryBuilder, $request->query->getInt('page', 1), 8);

        return $this->render('employe/index.html.twig', [
            'employes' => $employesPagination,
            'current_departement' => null,
        ]);
    }

    #Liste des employes par départements
    #[Route('/departements/{idDept}/employes', name: 'app_employe_dept', methods: ['GET'])]
    public function show(int $idDept, DepartementRepository $departementRepository, EmployeRepository $employeRepository, Request $request, PaginatorInterface $paginator): Response
    {
        $departement = $departementRepository->find($idDept);

        if (!$departement) {
            // Cas où le département n'est pas trouvé
            throw $this->createNotFoundException('Département non trouvé.');
        }

        // Trouver les employés associés à CE département
        $queryBuilder = $employeRepository->createQueryBuilder('e')->where('e.departement = :dept')->setParameter('dept', $departement)->getQuery();

        $employesPagination = $paginator->paginate($queryBuilder, $request->query->getInt('page', 1), 8);

        return $this->render('employe/index.html.twig', [
            'employes' => $employesPagination,
            'current_departement' => $departement, 
        ]);
    }

    #Query Params : Filtrer les employés
}
