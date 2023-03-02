<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry ;
use App\Repository\StudentRepository;
use App\Entity\Student ;
use Symfony\Component\HttpFoundation\Request;
use App\Form\StudentType ;

class StudentController extends AbstractController
{
    #[Route('/student', name: 'app_student')]
    public function index(): Response
    {
        return $this->render('student/index.html.twig', [
            'controller_name' => 'StudentController',
        ]);
    }

    #[Route('/getstudent', name: 'getstudent')]
    public function getstudent(StudentRepository $repo): Response
    {
        return $this->render('student/liste.html.twig', [
            'p' => $repo->findAll(),
        ]);
    }
    #[Route('/removesc/{id}', name: 'removesc')]
    public function removearticle(ManagerRegistry $mr,$id,StudentRepository $repo): Response
    {   $a=$repo->find($id);
        $em=$mr->getManager();
        $em->remove($a);
        $em->flush();
        return new Response('removed');
    }
    #[Route('/creates', name: 'creates')]
    public function creates(ManagerRegistry $mr, Request $req): Response
    {   
        $student = new Student();
        $form = $this->createForm(StudentType::class, $student);
        $form->handleRequest($req);

        if ($form->isSubmitted()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($student);
            $entityManager->flush();

            return $this->redirectToRoute('creates');
        }

        return $this->render('student/create.html.twig', [
            'form' => $form->createView()
        ]);

    }
    #[Route('/modifys/{id}', name: 'modifys')]
    public function modifyc(ManagerRegistry $mr, Request $req,$id): Response
    {  
        $student=$mr->getRepository(Student::class)->find($id);
        $form = $this->createForm(StudentType::class, $student);
        $form->handleRequest($req);

        if ($form->isSubmitted()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('getstudent');
        }

        return $this->render('student/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
