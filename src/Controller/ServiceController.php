<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ServiceController extends AbstractController
{
    #[Route('/service', name: 'app_service')]
    public function index(): Response
    {
        return $this->render('service/index.html.twig', [
            'controller_name' => 'ServiceController',
        ]);
    }

    #[Route('/service/home', name: 'app_service_Home')]
    public function goToIndex(){
        return $this->redirectToRoute("app_home");
    }

    #[Route('/service/{name}', name: 'app_showService')]
    public function showService($name)
    {
        return $this->render('service/showService.html.twig', [
            'controller_name' => 'ServiceController',
            'name'=>$name,
        ]);
    }


}
