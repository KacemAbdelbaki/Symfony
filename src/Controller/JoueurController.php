<?php

namespace App\Controller;

use App\Entity\Joueur;
use App\Form\JoueurType;
use App\Repository\JoueurRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class JoueurController extends AbstractController
{
    #[Route('/joueur', name: 'app_joueur')]
    public function index(): Response
    {
        return $this->render('joueur/index.html.twig', [
            'controller_name' => 'JoueurController',
        ]);
    }

    #[Route('/joueur/add', name:'add_joueur')]
    public function add(ManagerRegistry $doctrine, Request $req){
         $joueur = new Joueur();
         $form = $this->createForm(JoueurType::class, $joueur);
         $form->handleRequest($req);
         if($form->isSubmitted()){
             $em = $doctrine->getManager();
             $em->persist($joueur);
             $em->flush();
             return $this->redirectToRoute("display_joueur");
         }

         return $this->render("/joueur/add.html.twig",["form"=>$form->createView()]);
    }

    #[Route('/joueur/display', name:'display_joueur')]
    public function displayJoueur(JoueurRepository $rep){
        $list = $rep->findAll();
        return $this->render('/joueur/display.html.twig',['list'=>$list]);
    }

}
