<?php

namespace App\Controller;

use App\Entity\Matchee;
use App\Form\MatcheeType;
use App\Repository\MatcheeRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MatchController extends AbstractController
{
    #[Route('/match', name: 'app_match')]
    public function index(): Response
    {
        return $this->render('match/index.html.twig', [
            'controller_name' => 'MatchController',
        ]);
    }

    #[Route("/match/display", name:"display_match")]
    public function displayMatch(MatcheeRepository $rep){
        $matches=$rep->findAll();

        return $this->render("/match/display.html.twig",["list"=>$matches]);
    }

    #[Route("/match/add", name:"add_match")]
    public function addMatch(ManagerRegistry $doctrine, Request $req){
        $match = new Matchee();
        $form = $this->createForm(MatcheeType::class, $match);
        $form->handleRequest($req);
        if($form->isSubmitted()){
            $em= $doctrine->getManager();
            $em->persist($match);
            $em->flush();
            return $this->redirectToRoute("display_match");
        }

        return $this->render('/match/add.html.twig',['form'=>$form->createView()]);
    }

    #[Route("/match/remove/{id}", name:"remove_match")]
    public function removeMatch(ManagerRegistry $doctrine, MatcheeRepository $rep, $id){
        $em=$doctrine->getManager();
        $em->remove($rep->find($id));
        $em->flush();
        return $this->redirectToRoute("display_match");
    }

    #[Route("/match/modify/{id}", name:"modify_match")]
    public function modifyMatch(ManagerRegistry $doctrine, MatcheeRepository $rep, $id, Request $req){
        $match = $rep->find($id);
        $form = $this->createForm(MatcheeType::class, $match);
        $form->handleRequest($req);
        if($form->isSubmitted()){
            $em = $doctrine->getManager();
            $em->persist($match);
            $em->flush();

            return $this->redirectToRoute("display_match");
        }
        return $this->render('/match/modify.html.twig',['form'=>$form->createView()]);
    }
}
