<?php

namespace App\Controller;

use App\Entity\Employe;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EmployeController extends AbstractController
{
    /**
     * @Route("/employe", name="list_employe")
     */
    public function index(ManagerRegistry $doctrine): Response
    {
        $employes = $doctrine->getRepository(Employe::class)->findAll();

        return $this->render('employe/index.html.twig', [
            'employes' => $employes,
        ]);
    }
}
