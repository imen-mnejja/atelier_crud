<?php

namespace App\Controller;

use App\Entity\Classroom;
use App\Form\ClassroomType;
use App\Repository\ClassroomRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ClassroomController extends AbstractController
{
    #[Route('/classroom', name: 'app_classroom')]
    public function index(): Response
    {
        return $this->render('classroom/index.html.twig', [
            'controller_name' => 'ClassroomController',
        ]);
    }
    #[Route('/classroom/ajouter', name: 'ajouter_classroom')]
    public function Ajouter(ManagerRegistry $mr, Request $req): Response
    {
        //instance de l'objet 
        $a = new Classroom();
        $form = $this->createForm(ClassroomType::class, $a);
        $form->handleRequest($req); // analyser la requette http

        if ($form->isSubmitted()) {
            //pour l'insertion dans la bd 
            $em = $mr->getManager(); //necessaire 
            $em->persist($a); //preparation de la bd 
            $em->flush(); //l'execution
            //redirection 
            return $this->redirectToRoute('afficher_classroom');
        }
        return $this->renderForm('classroom/addclassroom.html.twig', [
            'formclassroom' => $form
        ]);
    }
    #[Route('/classroom/afficher', name: 'afficher_classroom')]
    public function afficher(ClassroomRepository $rep): Response
    {
        return $this->render('classroom/getclassroom.html.twig', [
            'classrooms' => $rep->findAll(),
        ]);
    }

    #[Route('/classroom/supprimer/{id}', name: 'supprimer_classroom')]
    public function supprimer(ManagerRegistry $mr, $id, ClassroomRepository $rep): Response
    {
        $a = $rep->find($id);
        $em = $mr->getManager();
        $em->remove($a);
        $em->flush();
        return $this->redirectToRoute('afficher_classroom');
    }

    #[Route('/classroom/modifier{id}', name: 'modifier_classroom')]
    public function Modifer(ManagerRegistry $mr, $id, Request $req): Response
    {

        $a = $mr->getRepository(Classroom::class)->find($id);
        $form = $this->createForm(ClassroomType::class, $a);
        $form->handleRequest($req); // analyser la requette http

        if ($form->isSubmitted()) {

            $em = $mr->getManager(); //necessaire 
            $em->flush(); //l'execution
            //redirection 
            return $this->redirectToRoute('afficher_classroom');
        }

        return $this->renderForm('classroom/addclassroom.html.twig', [
            'formclassroom' => $form
        ]);
    }
}
