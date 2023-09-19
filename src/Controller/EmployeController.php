<?php

namespace App\Controller;

use App\Entity\Employe;
use App\Form\EmployeType;
use App\Repository\EmployeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class EmployeController extends AbstractController
{
    #[Route('/employe', name: 'app_employe')]
    public function index(EmployeRepository $employeRepository): Response
    {
        $employes = $employeRepository->findBy([], ['nom' => 'ASC']);
        return $this->render('employe/index.html.twig', [
            'employes' => $employes,
        ]);
    }

    #[Route('/employe/new', name: 'new_employe')]
    public function new(Request $request, EntityManagerInterface $entityMananger): Response {
        
        $employe = new Employe();

        $form = $this->createForm(EmployeType::class, $employe);

        $form->handleRequest($request); 

        if ($form->isSubmitted() && $form->isValid()) {
            $employe = $form->getData();

            $entityMananger->persist($employe); // prepare PDO
            $entityMananger->flush(); // execute PDO


            return $this->redirectToRoute('app_employe');
        }
        
        return $this->render('employe/new.html.twig', [
            'formAddEmploye' => $form
        ]);
    }

    #[Route('/employe/{id}', name: 'show_employe')]
    public function show(Employe $employe) : Response {
        
        return $this->render('employe/show.html.twig', [
            'employe' => $employe
        ]);
    }
}
