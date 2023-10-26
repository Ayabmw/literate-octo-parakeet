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
        return $this->render('author/index.html.twig', 
        [
            'controller_n' => 'AuthorControllerrrrr',
            'variable2'=>'3A37'
        ]);
    }
    #[Route('/author2', name: 'app_author')]
    public function index2(): Response
    {
        return $this->render('author/index.html.twig', 
        [
            'controller_n' => 'AuthorControllerindex2',
            'variable2'=>'3A37'
        ]);
    }

    #[Route('/showAuthor/{name}', name: 'app_show_author')]
    public function showAuthor($name): Response
    {
        return $this->render('author/show.html.twig',[
            'mavariable'=>$name
        ]);
    }

    #[Route('/listauteurs', name: 'app_list_author')]
    public function list(): Response
    {
        $authors = array(
            array('id' => 1, 'picture' => 'images/Victor-Hugo.jpg','username' => 'Victor Hugo', 'email' => 'victor.hugo@gmail.com ', 'nb_books' => 100),
            array('id' => 2, 'picture' => 'images/william-shakespeare.jpg','username' => ' William Shakespeare', 'email' =>  ' william.shakespeare@gmail.com', 'nb_books' => 200 ),
            array('id' => 3, 'picture' => 'images/Taha_Hussein.jpg','username' => 'Taha Hussein', 'email' => 'taha.hussein@gmail.com', 'nb_books' => 300),
            );
            
        return $this->render('author/list.html.twig',
        ['maliste'=>$authors]);
    }

 #[Route('/detailsauthor/{id}', name: 'app_details_author')]
    public function details($id): Response
    {
        return $this->render('author/showauthor.html.twig', ['ida'=>$id]);
    }

    #[Route('/allAuthors', name: 'app_all_author')]
    public function getAll(ManagerRegistry $doctrine): Response
    {
        $repo=$doctrine->getRepository(Author::class);
        $authors=$repo->findAll();
        return $this->render('author/list.html.twig',
        ['maliste'=>$authors]);
        //return $this->render('author/showauthor.html.twig', ['ida'=>$id]);
    }
    #[Route('/addAuthor', name: 'app_add2_author')]

    public function addAuthor(Request $request): Response
    {
        $author = new Author();
        $form = $this->createForm(AuthorType::class, $author);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Handle file upload manually
            $file = $form['picture']->getData();
            if ($file) {
                $fileName = $this->generateUniqueFileName().'.'.$file->guessExtension();
                $file->move($this->getParameter('upload_directory'), $fileName);
                $author->setPicture($fileName);
            }

            // Save to the database
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($author);
            $entityManager->flush();

            return $this->redirectToRoute('app_add2_author');
        }

        return $this->render('author/addAuthor.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    // Generate a unique file name for uploaded files
    private function generateUniqueFileName()
    {
        return md5(uniqid());
    }
}