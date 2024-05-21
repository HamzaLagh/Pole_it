<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use App\Security\EmailVerifier;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Security\UsersAuthenticator;
use App\Service\Mailer;
use App\Service\RandomDataService;
use App\Service\RegistrationToken;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use Cocur\Slugify\Slugify;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;

class RegistrationController extends AbstractController
{


    public function __construct(private RandomDataService $randomDataService, private UsersAuthenticator $authenticator, private UserAuthenticatorInterface $userAuthenticator, private EntityManagerInterface $em, private RegistrationToken $jwt, private Mailer $mailer, EmailVerifier $emailVerifier, private UserRepository $userRepository)
    {
    }




    #[Route('/register', name: 'app_register')]
    public function register(Request $request, MailerInterface $mailer, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $slugify = new Slugify();

            $header = [
                'typ' => 'JWT',
                'alg' => 'HS256'
            ];

            // On crée le Payload
            $payload = [
                'user_id' => $user->getId()
            ];


            // On génère le token
            $token = $this->jwt->generate($header, $payload, $this->getParameter('app.jwtsecret'));

            $user->setCreatedAt(new \DateTimeImmutable());
            $user->setToken($token);
            $user->setBanner("dsdsdsdsds");
            $user->setPhoto($this->randomDataService->getImage());
            $entityManager->persist($user);
            $entityManager->flush();
            $entityManager->flush();
            $this->mailer->send(
                $this->getParameter('app.admin_email'),
                $user->getEmail(),
                "Vous devez activer votre compte pour pouvoir publier dans le forum",
                'registration',
                ['user' => $user, 'token' => $token, 'local' => $request->getLocale()]
            );
            $this->addFlash("success", "Inscription réussie");

            return $this->redirectToRoute('app_login');
        }
        $response = new Response(null, $form->isSubmitted() ? 422 : 200);
        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ], $response);
    }


    #[Route('/verif/{token}', name: 'verify_user')]
    public function verifyUser($token): Response
    {
        //On vérifie si le token est valide, n'a pas expiré et n'a pas été modifié
        if ($this->jwt->isValid($token) && !$this->jwt->isExpired($token) && $this->jwt->check($token, $this->getParameter('app.jwtsecret'))) {
            // On récupère le payload
            $payload = $this->jwt->getPayload($token);

            // On récupère le user du token
            $user = $this->userRepository->findOneByToken($token);

            //On vérifie que l'utilisateur existe et n'a pas encore activé son compte
            if ($user && !$user->isIsVerified()) {
                $user->setIsVerified(true);
                $this->em->flush($user);
                $this->addFlash('success', "Compte activé avec success");
                return $this->redirectToRoute('app_post_forum');
            }
        }
        // Ici un problème se pose dans le token
        $this->addFlash('danger', "Token invalide");
        return $this->redirectToRoute('app_login');
    }

    #[Route('/renvoi/verif', name: 'resend_verif')]
    public function resendVerif(Request $request): Response
    {
        $user = $this->getUser();

        if (!$user) {
            $this->addFlash('danger', "Connexion requise");
            return $this->redirectToRoute('app_login');
        }

        if ($user->isIsVerified()) {
            $this->addFlash('warning', "Votre compte est déjà activé");
            return $this->redirectToRoute('app_post_forum');
        }

        // On génère le JWT de l'utilisateur
        // On crée le Header
        $header = [
            'typ' => 'JWT',
            'alg' => 'HS256'
        ];

        // On crée le Payload
        $payload = [
            'user_id' => $user->getId()
        ];

        // On génère le token
        $token = $this->jwt->generate($header, $payload, $this->getParameter('app.jwtsecret'));
        $user->setToken($token);
        $user->setBanner("dsdsdsdsds");
        $this->em->flush();
        // On envoie un mail
        $this->mailer->send(
            $this->getParameter('app.admin_email'),
            $user->getEmail(),
            "Vous devez activer votre compte pour pouvoir publier une annonce",
            'registration',
            ['user' => $user, 'token' => $token, 'local' => $request->getLocale()]

        );
        $this->addFlash('success', "Vous avez reçu un mail pour activer votre compte");
        return $this->redirectToRoute('app_home');
    }
}
