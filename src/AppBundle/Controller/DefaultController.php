<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Book;
use AppBundle\Entity\User;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('default/index.html.twig', array(
            'base_dir' => realpath($this->container->getParameter('kernel.root_dir') . '/..') . DIRECTORY_SEPARATOR,
        ));

    }

    public function createAction()
    {
        $this->getRequest();
        $book = new Book();
        $book->setIsbn('9782070752447');
        $book->setTitle('Villa vortex');
        $book->setSummary('11 septembre 2001, un nouveau monde commence...');
        $book->setPublicationYear(2003);
        $book->setCreatedAt(new \Datetime());
        $book->setUpdatedAt(new \Datetime());
        $book->setIssueDate(new \Datetime());

        $em = $this->getDoctrine()->getManager();
        $em->persist($book);
        $em->flush();

        return new Response('Identifiant du livre ajouté : ' . $book->getId());
    }

    public function showAction($id)
    {
        $bookRepository = $this->getDoctrine() ->getRepository('AppBundle:Book');
        $book = $bookRepository->find($id);
        $user = $this->getDoctrine()->getRepository('AppBundle:User')->find($id);


         if (!$book) {
             throw $this->createNotFoundException('Aucun livre ne correspond à l\'id ' . $id);
         }

        if(!$user){
            return new Response('Livre consulté : ' . $book->getTitle().' (Disponible)');
        }
        if($user){
            $date = $book->getUpdatedAt();
            var_dump($date);
            return new Response('Livre consulté : ' . $book->getTitle().' emprunté par '.$user->getLastname().' le '. date('d/m/Y',strtotime($date->date)));
        }


    }
    public function updateAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $book = $em->getRepository('AppBundle:Book')->find($id);

        if (!$book) {
            throw $this->createNotFoundException('Aucun livre ne correspond à l\'id '.$id);
        }

        $book->setSummary('Attention ! Ouvrir un roman de Dantec c\'est comme entrer en religion...');
        $em->flush();

        return new Response('Livre modifié : ' . $book->getTitle());
    }
    public function deleteAction($id){
        $em = $this->getDoctrine()->getManager();
        $book = $em->getRepository('AppBundle:Book')->find($id);
        $em->remove($book);
        $em->flush();
        return new Response('Livre supprimé : ' . $book->getTitle());
    }

    //USERS
    public function createUserAction()
    {
        $user = new User();
        $user->setEmail('toto@gmail.com');
        $user->setPassword('toto');
        $user->setLastname('Toto');
        $user->setFirstname('Potato');
        $user->setAddress('64 Rue du Rire');
        $user->setZipCode(33000);
        $user->setBirthDate(new \Datetime());
        $user->setCreatedAt(new \Datetime());
        $user->setUpdatedAt(new \Datetime());

        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();

        return new Response('Identifiant de l\'utilisateur ajouté : ' . $user->getId());
    }
    public function locateUserAction($userId, $bookId){
        $em = $this->getDoctrine()->getManager();
        $book = $em->getRepository('AppBundle:Book')->find($bookId);
        $user = $em->getRepository('AppBundle:User')->find($userId);
        $user->addBook($book);
        $book->setUser($user);
        $book->setUpdatedAt(new \Datetime());
        $em->flush();

        return new Response('Livre '. $book->getTitle().' emprunté par '.$user->getLastname().' le '. date('d / m / Y'));
    }

}
