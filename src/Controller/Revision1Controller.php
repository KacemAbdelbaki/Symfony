<?php

namespace App\Controller;

use App\Entity\Person;
use App\Form\PersonType;
use App\Repository\PersonRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class Revision1Controller extends AbstractController
{
    #[Route('/revision1', name: 'app_revision1')]
    public function index(): Response
    {
        return $this->render('revision1/index.html.twig', [
            'controller_name' => 'Revision1Controller',
        ]);
    }

    #[Route('/revision1/{name}', name: 'revi_dis')]
    public function revidis($name){
        return $this->render("revision1/display.html.twig",['name'=>$name]);
    }

    #[Route('revision1/auth/showAuth', name: 'revi_show_auth')]
    public function displayAuth(){
        $authors = array(
            array('id' => 1, 'picture' => '/images/Victor-Hugo.jpg','username' => 'Victor Hugo', 'email' =>
                'victor.hugo@gmail.com ', 'nb_books' => 100),
            array('id' => 2, 'picture' => '/images/william-shakespeare.jpg','username' => ' William Shakespeare', 'email' =>
                ' william.shakespeare@gmail.com', 'nb_books' => 200 ),
            array('id' => 3, 'picture' => '/images/Taha_Hussein.jpg','username' => 'Taha Hussein', 'email' =>
                'taha.hussein@gmail.com', 'nb_books' => 300),
        );

        return $this->render('/revision1/authdisplay.html.twig',['auths'=>$authors]);
    }

    #[Route("/revision1/auth/{id}", name: "revi_show_auth_by_id")]
    public function showauth($id){
        $authors = array(
            array('id' => 1, 'picture' => '/images/Victor-Hugo.jpg','username' => 'Victor Hugo', 'email' =>
                'victor.hugo@gmail.com ', 'nb_books' => 100),
            array('id' => 2, 'picture' => '/images/william-shakespeare.jpg','username' => ' William Shakespeare', 'email' =>
                ' william.shakespeare@gmail.com', 'nb_books' => 200 ),
            array('id' => 3, 'picture' => '/images/Taha_Hussein.jpg','username' => 'Taha Hussein', 'email' =>
                'taha.hussein@gmail.com', 'nb_books' => 300),
        );
        foreach ($authors as $author){
            if($author['id'] == $id)
                return $this->render('/revision1/displayauthbyid.html.twig',['auth'=>$author]);
        }
        return null;
    }

    #[Route('/Person/dispAll', name:"Display_All")]
    public function dispAll(Request $req, PersonRepository $rep, ManagerRegistry $doctrine){
        $list = $rep->findAll();
        $person = new Person();
        $form = $this->createForm(PersonType::class, $person);
        $form->handleRequest($req);
        if($form->isSubmitted()){
            $em = $doctrine->getManager();
            $em->persist($person);
            $em->flush();
            return $this->redirectToRoute("Display_All");
        }
        return $this->render("/revision1/dispAll.html.twig",["people"=>$list,"form"=>$form->createView()]);
    }

    #[Route('/Person/delete/{id}', name:"Pers_Delete")]
    public function persDelete($id, ManagerRegistry $doctrine, PersonRepository $rep){
        $em = $doctrine->getManager();
        $em->remove($rep->find($id));
        $em->flush();
        return $this->redirectToRoute('Display_All');
    }

    #[Route('/Person/modify/{id}', name:"Pers_Modify")]
    public function persModify(Request $req,$id, ManagerRegistry $doctrine, PersonRepository $rep){
        $oldPerson = $rep->find($id);
        $newPerson = $oldPerson;

        $form = $this->createForm(PersonType::class, $newPerson);
        $form->handleRequest($req);
        if($form->isSubmitted()){
            $em = $doctrine->getManager();
            $em->persist($newPerson);
            $em->flush();
            return $this->redirectToRoute('Display_All');
        }

        return $this->render('revision1/modifyPers.html.twig',['oldPerson'=>$oldPerson, 'form'=>$form->createView()]);
    }
}

