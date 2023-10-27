<?php

namespace App\Controller;

use App\Entity\Student;
use App\Form\StudentType;
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

    #[Route("/test/{id}", name: "app_test")]
    public function test($id)
    {
        return new Response("hello world ".$id);
    }

    #[Route("/student/home", name: "app_Student_Home")]
    public function home(){
        return $this->redirectToRoute('app_student');
    }



    #[Route("/student/display", name:'disp_student')]
    public function dispStudent(StudentRepository $rep, ManagerRegistry $doc, Request $req){
        $list = $rep->findAll();

        $student = new Student();
        $form = $this->createForm(StudentType::class, $student);
        $form->handleRequest($req);
        if($form->isSubmitted()){
            $em= $doc->getManager();
            $em->persist($student);
            $em->flush();

            return $this->redirectToRoute('disp_student');
        }

        return $this->render('/student/display.html.twig',['students'=>$list, 'form'=>$form->createView()]);
    }

    #[Route("/student/add", name:'add_student')]
    public function addStudent(ManagerRegistry $doc, Request $req){
        $student = new Student();
        $form = $this->createForm(StudentType::class, $student);
        $form->handleRequest($req);
        if($form->isSubmitted()){
            $em= $doc->getManager();
            $em->persist($student);
            $em->flush();

            return $this->redirectToRoute('disp_student');
        }

        return $this->render('/student/add.html.twig',['form'=>$form->createView()]);
    }

    #[Route("/student/delete/{id}", name:'delete_student')]
    public function deleteStudent($id, StudentRepository $rep, ManagerRegistry $doc){
        $student = $rep->find($id);

        $em = $doc->getManager();
        $em->remove($student);
        $em->flush();

        return $this->redirectToRoute('disp_student');
    }

    #[Route("/student/modify/{id}", name:'modify_student')]
    public function modifyStudent($id, StudentRepository $rep, ManagerRegistry $doc, Request $req){
        $student = $rep->find($id);

        $form = $this->createForm(StudentType::class, $student);
        $form->handleRequest($req);
        if($form->isSubmitted()){
            $em= $doc->getManager();
            $em->persist($student);
            $em->flush();

            return $this->redirectToRoute('disp_student');
        }

        return $this->render("/student/modify.html.twig",['form'=>$form->createView()]);
    }








}
