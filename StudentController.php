<?php

namespace App\Controller;

use App\Entity\Classroom;
use App\Entity\Student;
use App\Form\StudentType;
use App\Repository\ClassroomRepository;
use App\Repository\StudentRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StudentController extends AbstractController
{
    #[Route('/student', name: 'app_student')]
    public function index(): Response
    {
        return $this->render('student/index.html.twig', [
            'controller_name' => 'StudentController',
        ]);
    }

    #[Route('/student/ajouter', name: 'ajouter_student')]
    public function Ajouter(ManagerRegistry $mr, Request $req): Response
    {
        //instance de l'objet 
        $a = new Student();
        $form = $this->createForm(StudentType::class, $a);
        $form->handleRequest($req); // analyser la requette http

        if ($form->isSubmitted()) {
            //pour l'insertion dans la bd 
            $em = $mr->getManager(); //necessaire 
            $em->persist($a); //preparation de la bd 
            $em->flush(); //l'execution
            //redirection 
            return $this->redirectToRoute('afficher_student');
        }

        /* return $this->render('student/addstudent.html.twig', [
            'formstudent' => $form->createView()
        ]);*/
        //  ou 
        return $this->renderForm('student/addstudent.html.twig', [
            'formstudent' => $form
        ]);
    }

    #[Route('/student/afficher', name: 'afficher_student')]
    public function afficher(StudentRepository $rep, ClassroomRepository $c): Response
    {


        return $this->render('student/getstudent.html.twig', [
            'students' => $rep->findAll(), 'classes' => $c->findAll()
        ]);
        // return $this->render('student/getstudent.html.twig', array('students' => $rep->findAll()));
    }

    #[Route('/student/supprimer/{nsc}', name: 'supprimer_student')]
    public function supprimer(ManagerRegistry $mr, $nsc, StudentRepository $rep): Response
    {
        $a = $rep->find($nsc);
        $em = $mr->getManager();
        $em->remove($a);
        $em->flush();
        return $this->redirectToRoute('afficher_student');
    }

    #[Route('/student/modifier{nsc}', name: 'modifier_student')]
    public function Modifer(ManagerRegistry $mr, $nsc, Request $req): Response
    {
        //recuperer le student avec son nsc 
        $a = $mr->getRepository(Student::class)->find($nsc);
        $form = $this->createForm(StudentType::class, $a);
        $form->handleRequest($req); // analyser la requette http

        if ($form->isSubmitted()) {

            $em = $mr->getManager(); //necessaire 
            $em->flush(); //l'execution
            //redirection 
            return $this->redirectToRoute('afficher_student');
        }

        return $this->renderForm('student/addstudent.html.twig', [
            'formstudent' => $form
        ]);
    }

    /*  public function trouver(ClassroomRepository $c, int $id): Response
    {
        return $this->render('student/getstudent.html.twig', [
            'idc' => $c->findOneBy($id),
        ]);
    }*/
}
