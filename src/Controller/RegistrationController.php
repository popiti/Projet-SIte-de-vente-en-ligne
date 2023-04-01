<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Security\LoginAuthenticator;
use App\Service\PasswordStrength;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;

class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'app_register')]
    public function register( Request $request, UserPasswordHasherInterface $userPasswordHasher, UserAuthenticatorInterface $userAuthenticator, LoginAuthenticator $authenticator, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $lienPanier = new PasswordStrength();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $user->setRoles(["ROLE_CLIENT"]);
            $pswdStrength = $lienPanier->pswdStrength(
                $form->get('plainPassword')->getData()
            );
            $this->addFlash('info',$pswdStrength);
            $entityManager->flush();
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $user->setNom(
                $form->get('nom')->getData()
            );
            $user->setPrenom(
                $form->get('prenom')->getData()
            );
            $user->setBirthdate(
                $form->get('birthdate')->getData()
            );

            $entityManager->persist($user);
            $entityManager->flush();
            dump($user);
            // do anything else you need here, like send an email
            $this->addFlash('info',' Vous avez réussi à créer votre compte avec succés !');
            return $userAuthenticator->authenticateUser(
                $user,
                $authenticator,
                $request
            );
        }
        elseif($form->isSubmitted())
        {
            $this->addFlash('info','Votre compte n\'a pas pu etre créé ');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
}
