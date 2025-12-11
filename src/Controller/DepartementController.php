<?php

namespace App\Controller;

use App\DTO\DepartementListDto;
use App\Entity\Departement;
use App\Form\DepartementType;
use App\Repository\DepartementRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class DepartementController extends AbstractController
{
    /**
     * liste des departements ==>GET
     * creer un departement ==>POST
     */

    public function __construct(private readonly DepartementRepository $departementRepository,
    ) 
    {
    }
    #[Route('departement/list', name: 'app_departement_list', methods: ["GET", "POST"])]
    public function list(Request $request): Response
        {
            // $departement= $this->departementRepository->findBy(1);
            $departement =new Departement();
            $form=$this->createForm(DepartementType::class, $departement);
            $form->handleRequest($request);
            if($form->isSubmitted() && $form->isValid()){
                $this->departementRepository->save($departement, true);
                $this->addFlash('success', 'Département ajouté avec succès !');
                return $this->redirectToRoute('app_departement_list');
            }

            $page=$request->query->get('page', 1);
            $offset=($page-1)*$this->getParameter('LIMIT_PAR_PAGE');
            // entities
            $departements= $this->departementRepository->findBy([],[
                "id"=>"desc"
            ], $this->getParameter('LIMIT_PAR_PAGE'), $offset);
            // dto
            $departementsDto=DepartementListDto::fromEntities($departements);


            $count=$this->departementRepository->count([]);
            $nbrePage=ceil($count/$this->getParameter('LIMIT_PAR_PAGE'));
            return $this->render('departement/list.html.twig', [
                'departements' => $departementsDto,
                'nbrePage'=>$nbrePage,
                'pageEncours'=>$page,
                'formDept'=>$form->createView()
            ]);
        }
}
