<?php

namespace App\Controller;

use App\Entity\Author;
use App\Form\AuthorType;
use App\Repository\AuthorRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AuthorController extends AbstractController
{
    #[Route('/author', name: 'app_author')]
    public function index(): Response
    {
        return $this->render('author/index.html.twig', [
            'controller_name' => 'AuthorController',
        ]);
    }

    #[Route('/author/show', name: 'author_list_show')]
    public function List(){
        $authors = array(
            array(
                'id' => 1,
                'picture' => '/images/Victor-Hugo.jpg',
                'username' => 'Victor Hugo',
                'email' => 'victor.hugo@gmail.com ',
                'nb_books' => 100
            ),
            array(
                'id' => 2,
                'picture' => '/images/william-shakespeare.jpg',
                'username' => 'William Shakespeare',
                'email' => 'william.shakespeare@gmail.com',
                'nb_books' => 200
            ),
            array(
                'id' => 3,
                'picture' => '/images/Taha_Hussein.jpg',
                'username' => 'Taha Hussein',
                'email' => 'taha.hussein@gmail.com',
                'nb_books' => 300
            )
        );
        return $this->render("author/list.html.twig",['authors'=>$authors]);
    }

    #[Route('/author/show/{name}', name: 'author_show')]
    public function showAuthor($name): Response
    {
        return $this->render("author/show.html.twig",['name'=>$name]);
    }

    #[Route('/author/details/{id}', name: 'author_details')]
    public function auhtorDetails($id): Response
    {
        $authors = array(
            array(
                'id' => 1,
                'picture' => '/images/Victor-Hugo.jpg',
                'username' => 'Victor Hugo',
                'email' => 'victor.hugo@gmail.com ',
                'nb_books' => 100
            ),
            array(
                'id' => 2,
                'picture' => '/images/william-shakespeare.jpg',
                'username' => 'William Shakespeare',
                'email' => 'william.shakespeare@gmail.com',
                'nb_books' => 200
            ),
            array(
                'id' => 3,
                'picture' => '/images/Taha_Hussein.jpg',
                'username' => 'Taha Hussein',
                'email' => 'taha.hussein@gmail.com',
                'nb_books' => 300
            )
        );
        return $this->render("author/showAuthor.html.twig",['id'=>$id, 'authors'=>$authors]);
    }

    #[Route('/author/db/authors', name: 'author_db_authors')]
    public function db_authors(AuthorRepository $repo): Response
    {
        $list= $repo->findAll();
        return $this->render("/author/db.html.twig",['authors'=>$list]);
    }

    #[Route('/author/db/add', name: 'author_db_add')]
    public function addAuthor(ManagerRegistry $doctrine){
        $author= new Author();
        $author->setEmail($_GET['email']);
        $author->setUsername($_GET['username']);

        $em= $doctrine->getManager();
        $em->persist($author);
        $em->flush();

        return $this->redirectToRoute('author_db_authors');
    }

    #[Route('/author/db/delete/{id}', name: 'author_db_delete')]
    public function deleteAuthor(AuthorRepository $repo,ManagerRegistry $doctrine, $id){
        $em= $doctrine->getManager();
        $em->remove($repo->find($id));
        $em->flush();

        return $this->redirectToRoute('author_db_authors');
    }

    #[Route('/author/db/edit/{id}', name: 'author_db_edit')]
    public function editAuthor(AuthorRepository $repo, $id){
       $author= $repo->find($id);

        return $this->render("/author/edit.html.twig",['author'=>$author]);
    }

    #[Route('/author/edit/form/{id}', name: 'author_db_edit_form')]
    public function editFormAuthor(AuthorRepository $repo,ManagerRegistry $doctrine,$id){
        $author=$repo->find($id);
        if($_GET['email'] != "")
            $author->setEmail($_GET['email']);
        if($_GET['username'] != "")
            $author->setUsername($_GET['username']);

        $em= $doctrine->getManager();
        $em->persist($author);
        $em->flush();

        return $this->redirectToRoute('author_db_authors');
    }

    // Form
    #[Route('add', name:"add")]
    public function add(Request $request,ManagerRegistry $doctrine){
        $author = new Author();

        $form = $this->createForm(AuthorType::class, $author,);
        $form->handleRequest($request);
        if($form->isSubmitted()){
            $em= $doctrine->getManager();
            $em->persist($author);
            $em->flush();
            return $this->redirectToRoute('author_db_authors');
        }

        return $this->render("/author/add.html.twig",['form'=>$form->createView()]);
    }

    #[Route('modify/{id}', name:"modify")]
    public function modify(Request $request,ManagerRegistry $doctrine, AuthorRepository $repo, $id){
        $author = $repo->find($id);

        $form = $this->createForm(AuthorType::class, $author,);
        $form->handleRequest($request);
        if($form->isSubmitted()){
            $em= $doctrine->getManager();
            $em->persist($author);
            $em->flush();
            return $this->redirectToRoute('author_db_authors');
        }

        return $this->render("/author/edite.html.twig",['form'=>$form->createView()]);
    }
}
