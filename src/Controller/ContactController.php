<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Form\ContactType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class ContactController extends AbstractController
{

    public function __construct(private EntityManagerInterface $em)
    {
    }



    #[Route('/contact', name: 'app_contact')]
    public function index(Request $request)
    {
        $session = $request->getSession();
        $session->set('menu', 'contact');

        $contact = new Contact();
        $form = $this->createForm(ContactType::class, $contact);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {


            $contact = $form->getData();

            $contact->setCreatedAt(new \DateTimeImmutable());
            $contact->setPays("Cameroun");
            $contact->setNom($form->get('nom')->getData());
            $contact->setContent($form->get('content')->getData());
            $contact->setEmail($form->get('email')->getData());
            $contact->setTelephone(strval($form->get('telephone')->getData()));
            $this->em->persist($contact);
            $this->em->flush();
            $this->addFlash('success', "Votre message a été enregistré, nous vous contacterons très bientôt!");
            return $this->redirectToRoute('app_home');
        }



        $response = new Response(null, $form->isSubmitted() ? 422 : 200);
        return $this->render('contact/contact.html.twig', [
            'form' => $form,

        ], $response);
    }


    #[Route('/activites/enfants', name: 'app_enfants')]
    public function activitesEnfants(Request $request)
    {

        return $this->render('posts/enfants.html.twig');
    }

    #[Route('/activites/nocturnes', name: 'app_nuits_activites')]
    public function activitesNocturnes(Request $request)
    {

        return $this->render('posts/nuit.html.twig');
    }
}
