<?php

namespace App\Controller;

use App\Entity\Book;
use App\Form\BookType;
use App\Form\RefType;
use App\Repository\BookRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BookController extends AbstractController
{
    #[Route('/book', name: 'app_book')]
    public function index(): Response
    {
        return $this->render('book/index.html.twig', [
            'controller_name' => 'BookController',
        ]);
    }

    #[Route('/book/add', name:"add")]
    public function addBook(Request $req, ManagerRegistry $doctrine){

        $book = new Book();
        $form = $this->createForm(BookType::class, $book);
        $form->handleRequest($req);
        if($form->isSubmitted()){
            $book->setPublished("true");
            $book->getAuthors()->setNbBooks($book->getAuthors()->getNbBooks()+1);
            $em = $doctrine->getManager();
            $em->persist($book);
            $em->flush();
        }

        return $this->render("/book/addform.html.twig",[
            'form'=>$form->createView()
        ]);
    }

    #[Route('/book/books', name:"display_books")]
    public function disBook(BookRepository $rep, ManagerRegistry $doctrine, Request $req,){
//        $list = $rep->findAll();
        $list = $rep->QBfindall();

        $book = new Book();
        $form = $this->createForm(BookType::class, $book);
        $form->handleRequest($req);
        if($form->isSubmitted()){
            $book->setPublished("true");
            $book->getAuthors()->setNbBooks($book->getAuthors()->getNbBooks()+1);
            $em = $doctrine->getManager();
            $em->persist($book);
            $em->flush();

            return $this->redirectToRoute("display_books");
        }

        return $this->render('/book/displayBooks.html.twig',["books"=>$list, 'form'=>$form->createView()]);
    }

    #[Route('/book/booksDQL', name:"display_books")]
    public function disBookDQL(BookRepository $rep, ManagerRegistry $doctrine, Request $req,){
        $list = $rep->findAll();

        $form = $this->createForm(RefType::class);
        $form->handleRequest($req);
        if($form->isSubmitted()){
            $ref = $form->getData();
            $list = $rep->selectByRef($ref);
            return $this->render('/book/displayBooks.html.twig',["books"=>$list, 'form'=>$form->createView()]);
        }

        return $this->render('/book/displayBooks.html.twig',["books"=>$list, 'form'=>$form->createView()]);
    }


}
