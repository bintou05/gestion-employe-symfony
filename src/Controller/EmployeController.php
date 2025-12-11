<?php

namespace App\Controller;

use App\DTO\DepartementListDto;
use App\DTO\EmployeListDto;
use App\DTO\EmployeSearchFormDto;
use App\Entity\Employe;
use App\Form\EmployeSearchType;
use App\Form\EmployeType;
use App\Repository\DepartementRepository;
use App\Repository\EmployeRepository;
use App\service\GenerateNumeroService;
use App\service\Impl\FileUploaderServiceImpl;
use Doctrine\ORM\Mapping\Id;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class EmployeController extends AbstractController
{

    public function __construct(private readonly EmployeRepository $employeRepository,
        private readonly DepartementRepository $departementRepository)
    {
    }

    /**
     * Liste des employés avec filtre par département
     * liste des employes ==>GET
     * créer un employe ==>POST
     * path /employe/list/{idDept}'
     *     url example: /employe/list/3  (3 est l'id du département)
     * path /employe/list/{idDept?}'
     *    url example: /employe/list/   (affiche tous les employés)
     *    url example: /employe/list/2  (affiche les employés du département 2)
     * 
     */
    #[Route('/employe/list/{idDept?}', name: 'app_employe_list', methods: ['GET','POST'])]
    public function list($idDept = null, Request $request): Response
    {
        $departement = null;
        $filtre = [
            "isArchived" => false
        ];
        
        if ($idDept != null && $idDept !== '') {
            $filtre["departement"] = $idDept;
            $departement = $this->departementRepository->find($idDept);
        }
        $searchFormDto = new EmployeSearchFormDto();
        $form=$this->createForm(EmployeSearchType::class, $searchFormDto,[
            'method'=>'GET',
            // 'action'=>$this->generateUrl('app_employe_list', ['idDept' => $idDept]),
            'departement_default'=>$departement,
            'csrf_protection' => false,


        ]);
        $form->handleRequest($request);
        //applique le filtre issus du formulaire
        if ($form->isSubmitted() && $form->isValid()){
            if ($searchFormDto->isArchived !== null) {
                $filtre["isArchived"] = $searchFormDto->isArchived;
            }

            if ($searchFormDto->departement !== null) {
                // findBy accepte l'entité, pas besoin d'ID donc
                $filtre["departement"] = $searchFormDto->departement;
            }

            if ($searchFormDto->numero) {
                // Cohérent avec Departement :  on reste sur une égalité simple
                $filtre["numero"] = $searchFormDto->numero;
            }
        }

        $page=$request->query->get('page', 1);
        $offset=($page-1)*$this->getParameter('LIMIT_PAR_PAGE');
        $count=$this->employeRepository->count($filtre);
        $nbrePage=ceil($count/$this->getParameter('LIMIT_PAR_PAGE'));
        // Utilisez le filtre que vous avez créé
        $employes = $this->employeRepository->findBy($filtre, [
            "id"=>"desc"
        ], $this->getParameter('LIMIT_PAR_PAGE'), $offset);
        $employeDto =EmployeListDto::fromEntities($employes);
        $departementDo=$departement!=null?DepartementListDto::fromEntitie($departement):null;
        return $this->render('employe/list.html.twig', [
            'employes' => $employeDto,
            'idDept' => $idDept,
            'departement' => $departementDo,
            'nbrePage'=>$nbrePage,
            'pageEncours'=>$page,
            'formSearchEmp' => $form->createView(),
        ]);
    }

    /**
     * Créer un employé
     */
    #[Route('/employe/add', name: 'app_employe_add', methods: ['GET', 'POST'])]
    public function save(Request $request,GenerateNumeroService $numService,FileUploaderServiceImpl $fileUploader): Response
    {
        $employe = new Employe();
        $employe->setNumero($numService->generateNumero());
        $form = $this->createForm(EmployeType::class, $employe);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // logique pour gérer les champs non mappés
            $pays = $form->get('pays')->getData();
            $ville = $form->get('ville')->getData();
            $rue = $form->get('rue')->getData();
            $employe->setAdresse("$rue, $ville, $pays");
            $photoFile=$employe->getPhotoFile();
            if($photoFile){
                $photoName=$fileUploader->upload($photoFile);
                $employe->setPhoto($photoName);
            }
            $this->employeRepository->save($employe, true);
            $this->addFlash('success', 'Employé ajouté avec succès !');

            return $this->redirectToRoute('app_employe_list');
        }
        return $this->render('employe/form.html.twig', [
            'formEmp' => $form->createView(),
        ]);
    }
}