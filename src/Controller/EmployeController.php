<?php

namespace App\Controller;

use App\Entity\Employe;
use App\Form\EmployeType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class EmployeController extends AbstractController
{
    /**
     * @Route("/employe", name="index_employe")
     */
    public function index(ManagerRegistry $doctrine): Response
    {
        $employes = $doctrine->getRepository(Employe::class)->findAll();

        return $this->render('employe/index.html.twig', [
            'employes' => $employes,
        ]);
    }

    /**
     * @Route("/employe/add", name="add_employe")
     * @Route("/employe/update/{id}", name="update_employe")
     */
    public function add(ManagerRegistry $doctrine, Employe $employe = null, Request $request, SluggerInterface $slugger)
    {
        if (!$employe) {
            $employe = new Employe();
        }

        $entityManager = $doctrine->getManager();
        $form = $this->createForm(EmployeType::class, $employe);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $employe = $form->getData();

            // Upload d'image

            $imagesUpload = $form->get('image')->getData();

            if ($imagesUpload) {
                $orignalFilename = pathinfo($imagesUpload->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($orignalFilename);
                $fileName = $safeFilename.'-'.uniqid().'.'.$imagesUpload->guessExtension();

                try {
                    $imagesUpload->move($this->getParameter('images_upload'), $fileName);
                } catch (FileException $e) {
                    throw $e->getMessage($fileName);
                }

                $employe->setImageFileName($fileName);
            }
            $entityManager->persist($employe);
            $entityManager->flush();

            return $this->redirectToRoute('index_employe');
        }

        return $this->render('employe/add.html.twig', [
            'formEmploye' => $form->createView(),
        ]);
    }

    /**
     * @Route("/employe/delete/{id}", name="delete_employe")
     */
    public function delete(ManagerRegistry $doctrine, Employe $employe)
    {
        $entityManager = $doctrine->getManager();

        $entityManager->remove($employe);

        $fileName = $employe->getImageFileName();

        if (file_exists($fileName)) {
            unlink($fileName);
        }
        $entityManager->flush();

        return $this->redirectToRoute('index_employe');
    }

    /**
     * @Route("/employe/{id}", name="show_employe")
     */
    public function show(Employe $employe): Response
    {
        return $this->render('employe/show.html.twig', [
            'employe' => $employe,
        ]);
    }
}
