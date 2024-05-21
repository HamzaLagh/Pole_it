<?php

namespace App\Controller;

use App\Repository\UserRepository;
use App\Security\EmailVerifier;
use App\Service\Mailer;
use App\Form\ResetPasswordFormType;
use App\Form\ResetPasswordRequestFormType;
use App\Service\RegistrationToken;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class SecurityController extends AbstractController
{

    public function __construct(private TranslatorInterface $translator, private RegistrationToken $jwt, private UserPasswordHasherInterface $passwordHasher, private TokenGeneratorInterface $tokenGenerator, EmailVerifier $emailVerifier, private Mailer $mailer, private UserRepository $userRepository, private EntityManagerInterface $em)
    {
    }



    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }



    #[Route('/oubli/pass', name: 'forgotten_password')]
    public function forgottenPassword(
        Request $request,
    ): Response {
        $form = $this->createForm(ResetPasswordRequestFormType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //On va chercher l'utilisateur par son email
            $user = $this->userRepository->findOneByEmail($form->get('email')->getData());

            // On vérifie si on a un utilisateur
            if ($user) {
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
                // On génère un token de réinitialisation
                //$token = $this->tokenGenerator->generateToken();
                $user->setToken($token);
                //$this->em->persist($user);
                $this->em->flush();

                // On génère un lien de réinitialisation du mot de passe
                $url = $this->generateUrl('reset_pass', ['_local' => $request->getLocale(), 'token' => $token], UrlGeneratorInterface::ABSOLUTE_URL);

                // On crée les données du mail
                $context = compact('url', 'user');

                // Envoi du mail
                $this->mailer->send(
                    $this->getParameter('app.admin_email'),
                    $user->getEmail(),
                    "Réinitialisation de mot de passe",
                    'password_reset',
                    $context
                );

                $this->addFlash('success', "Vous avez reçu un mail pour réinitialiser votre mot de passe");
                return $this->redirectToRoute('app_login');
            }
            // $user est null
            $this->addFlash('danger', "Problème de connexion ou adresse email non trouvée");
            return $this->redirectToRoute('app_login');
        }

        return $this->render('security/reset_password_request.html.twig', [
            'requestPassForm' => $form->createView()
        ]);
    }

    #[Route('/pass/forget/{token}', name: 'reset_pass')]
    public function resetPass(
        string $token,
        Request $request



    ): Response {
        // On vérifie si on a ce token dans la base
        $user = $this->userRepository->findOneByToken($token);

        if ($user) {
            $form = $this->createForm(ResetPasswordFormType::class);

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                // On efface le token
                $user->setToken('');
                $user->setPassword(
                    $this->passwordHasher->hashPassword(
                        $user,
                        $form->get('password')->getData()
                    )
                );
                $this->em->persist($user);
                $this->em->flush();

                $this->addFlash('success', "Mot de passe changé avec success");
                return $this->redirectToRoute('app_login');
            }

            return $this->render('security/reset_password.html.twig', [
                'passForm' => $form->createView()
            ]);
        }
        $this->addFlash('danger', "Le token est invalide ou a expiré");
        return $this->redirectToRoute('app_login');
    }
}
