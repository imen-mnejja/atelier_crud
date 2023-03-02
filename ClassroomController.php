<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry ;
use App\Repository\ClassroomRepository;
use App\Entity\Classroom ;
use Symfony\Component\HttpFoundation\Request;
use App\Form\ClassroomType ;

class ClassroomController extends AbstractController
{
    #[Route('/classroom', name: 'app_classroom')]
    public function index(): Response
    {
        return $this->render('classroom/index.html.twig', [
            'controller_name' => 'ClassroomController',
        ]);
    }
    #[Route('/listc', name: 'listc')]
    public function listc(ClassroomRepository $repo): Response
    {
        return $this->render('classroom/list.html.twig', [
            'p' => $repo->findAll(),
        ]);
    }
    #[Route('/removec/{id}', name: 'removec')]
    public function removec(ManagerRegistry $mr,$id,ClassroomRepository $repo): Response
    {   $a=$repo->find($id);
        $em=$mr->getManager();
        $em->remove($a);
        $em->flush();
        return new Response('removed');
    }
    #[Route('/createc', name: 'createc')]
    public function createc(ManagerRegistry $mr, Request $req): Response
    {   
        $classroom = new Classroom();
        $form = $this->createForm(ClassroomType::class, $classroom);
        $form->handleRequest($req);

        if ($form->isSubmitted()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($classroom);
            $entityManager->flush();

            return $this->redirectToRoute('createc');
        }

        return $this->render('classroom/create.html.twig', [
            'form' => $form->createView()
        ]);

    }
    #[Route('/modifyc/{id}', name: 'modifyc')]
    public function modifyc(ManagerRegistry $mr, Request $req,$id): Response
    {  
        $classroom=$mr->getRepository(Classroom::class)->find($id);
        $form = $this->createForm(ClassroomType::class, $classroom);
        $form->handleRequest($req);

        if ($form->isSubmitted()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('listc');
        }

        return $this->render('classroom/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
