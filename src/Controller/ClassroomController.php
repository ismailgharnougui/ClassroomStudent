<?php

namespace App\Controller;
use App\Entity\Classroom;
use App\Form\ClassroomType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ClassroomRepository;
use Symfony\Component\HttpFoundation\Request;

class ClassroomController extends AbstractController
{
    #[Route('/classroom', name: 'app_classroom')]
    public function index(): Response
    {
        return $this->render('classroom/index.html.twig', [
            'controller_name' => 'ClassroomController',
        ]);
    }
    #[Route('/AfficherC', name: 'app_classroomC')]

    public function Afficher(ClassroomRepository $repo)
    {
            $class = $repo->findAll();
            return $this->render('Classroom/list.html.twig',['sabrina'=>$class]);
    }

    #[Route('/Add', name: 'app_Add')]

    public function  Add (Request  $request)
    {
        $class=new Classroom();
        $form =$this->CreateForm(ClassroomType::class,$class);
      $form->add('Ajouter',SubmitType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        {
            $em=$this->getDoctrine()->getManager();
            $em->persist($class);  
            $em->flush();
            return $this->redirectToRoute('app_classroomC');
        }
        return $this->render('classroom/Add.html.twig',['c'=>$form->createView()]);
    
    }

    #[Route('/edit/{id}', name: 'app_edit')]
    public function edit(ClassroomRepository $repository, $id, Request $request)
    {
        $classroom = $repository->find($id);
        $form = $this->createForm(ClassroomType::class, $classroom);
        $form->add('Modifier', SubmitType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush(); // Correction : Utilisez la méthode flush() sur l'EntityManager pour enregistrer les modifications en base de données.
            return $this->redirectToRoute("app_classroomC");
        }

        return $this->render('classroom/edit.html.twig', [
            'f' => $form->createView(),
        ]);
    }

    #[Route('/delete/{id}', name: 'app_delete')]
    public function delete($id, ClassroomRepository $repository)
    {
        $class = $repository->find($id);

        if (!$class) {
            throw $this->createNotFoundException('Auteur non trouvé');
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($class);
        $em->flush();

        
        return $this->redirectToRoute('app_classroomC');
    }
}
