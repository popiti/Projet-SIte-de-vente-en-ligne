<?php

namespace App\Controller\Sandbox;

use App\Entity\User;
use App\Form\MonProfile;
use App\Security\LoginAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;
use function PHPUnit\Framework\throwException;

#[Route('/sandbox', name: 'app_sandbox')]
class UtilisateurController extends AbstractController
{
    #[Route('/profile/{id}', name: '_profile')]
    public function profile(Request $request,UserPasswordHasherInterface $userPasswordHasher,UserAuthenticatorInterface
    $userAuthenticator,LoginAuthenticator $authenticator ,EntityManagerInterface $em,int $id): Response
    {
        $userRepo = $em->getRepository(User::class);
        $users = $userRepo->find($id);
        $form = $this->createForm(MonProfile::class, $users);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            if (is_null($form->get('plainPassword')->getData()))
            {

            }
            else
            {
                $users->setPassword(
                    $userPasswordHasher->hashPassword(
                        $users,
                        $form->get('plainPassword')->getData()
                    )
                );
            }
            $em->persist($users);
            $em->flush();
            // do anything else you need here, like send an email

        }
        if (is_null($users))
    {
        //$this->addFlash('info', 'suppression film' . $id . 'reussie');
        throw new NotFoundHttpException('utilisateur' . $id . 'not found');
    }
        $args = array('id'=>$id,'registrationForm'=>$form->createView(),);
        return $this->render('/Sandbox/utilisateur/profile.html.twig', $args);
    }

}
