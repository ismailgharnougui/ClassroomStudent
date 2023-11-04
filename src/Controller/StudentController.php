<?php

namespace App\Controller;
use App\Entity\Student;
use App\Form\StudentType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\StudentRepository;
use Symfony\Component\HttpFoundation\Request;


class StudentController extends AbstractController
{
    #[Route('/student', name: 'app_student')]
    public function index(): Response
    {
        return $this->render('student/index.html.twig', [
            'controller_name' => 'StudentController',
        ]);
    }
    #[Route('/AfficherS', name: 'app_studentS')]

    public function Afficher(StudentRepository $repo)
    {
            $student = $repo->findAll();
            return $this->render('student/list.html.twig',['students'=>$student]);
    }
    #[Route('/AddS', name: 'app_AddS')]

    public function  Add (Request  $request)
    {
        $student=new Student();
        $form =$this->CreateForm(StudentType::class,$student);
      $form->add('Ajouter',SubmitType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        {
            $em=$this->getDoctrine()->getManager();
            $em->persist($student);  
            $em->flush();
            return $this->redirectToRoute('app_studentS');
        }
        return $this->render('student/Add.html.twig',['s'=>$form->createView()]);
    
    }

    #[Route('/editS/{nsc}', name: 'app_editS')]
    public function edit(StudentRepository $repository, $nsc, Request $request)
    {
        $student = $repository->find($nsc);
        $form = $this->createForm(StudentType::class, $student);
        $form->add('Modifier', SubmitType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush(); // Correction : Utilisez la méthode flush() sur l'EntityManager pour enregistrer les modifications en base de données.
            return $this->redirectToRoute("app_studentS");
        }

        return $this->render('Student/edit.html.twig', [
            'f' => $form->createView(),
        ]);
    }
    #[Route('/deleteS/{nsc}', name: 'app_deleteS')]
    public function delete($nsc, StudentRepository $repository)
    {
        $student = $repository->find($nsc);

        if (!$student) {
            throw $this->createNotFoundException('Student non trouvé');
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($student);
        $em->flush();

        
        return $this->redirectToRoute('app_studentS');
    }
}
