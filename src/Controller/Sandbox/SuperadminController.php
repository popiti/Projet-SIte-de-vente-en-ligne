<?php

namespace App\Controller\Sandbox;

use App\Entity\User;
use App\Form\MonProfile;
use App\Form\RegisterAdmin;
use App\Security\LoginAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;

#[Route('superadmin', name: 'app_superadmin')]
class SuperadminController extends AbstractController
{
    #[Route('/admin/create', name: '_createadmin')]
    public function createAdminAction(EntityManagerInterface $em, Request $request, UserPasswordHasherInterface $userPasswordHasher, UserAuthenticatorInterface $userAuthenticator, LoginAuthenticator $authenticator): Response
    {
        $user = new User();
        $form = $this->createForm(RegisterAdmin::class,$user);
        $form->handleRequest($request);

        if ($form->isSubmitted() )
        {
            if ($form->isValid())
            {
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
                $user->setRoles(["ROLE_ADMIN"]);
                $em->persist($user);
                $em->flush();
                dump($user);
            }
            else
            {
                $this->addFlash('info', 'Ajout incorrect');
            }
        }
        $args = array(
            'registrationAdmin'=>$form->createView()
        );

        return $this->render('superadmin/createAdmin.html.twig',$args);
    }

    #[Route('/admin/modif/{id}', name: '_modifadmin',requirements: ['id' => '[1-9]\d*'])]
    public function modifAdminAction(EntityManagerInterface $em, Request $request,UserPasswordHasherInterface $userPasswordHasher, int $id): Response
    {
        $userRepo = $em->getRepository(User::class);
        $users = $userRepo->find($id);
        $form = $this->createForm(MonProfile::class, $users);
        if(!in_array("ROLE_ADMIN",$users->getRoles()))
        {
            $this->addFlash('info','Vous n\'avez pas le droit d\'accéder à cette page');
            return $this->redirectToRoute('app_sandbox_listadmin');
        }
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            if (!is_null($form->get('plainPassword')->getData()))
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
            return $this->redirectToRoute("app_sandbox_listadmin");
        }
        if (is_null($users))
        {
            //$this->addFlash('info', 'suppression film' . $id . 'reussie');
            throw new NotFoundHttpException('utilisateur' . $id . 'not found');
        }
        $args = array(
            'id'=>$id,'registrationForm'=>$form->createView(),'nom'=>$users->getNom(),
            );
        return $this->render('/superadmin/modifadmin.html.twig', $args);
    }

    #[Route('/admin/delete/{id}',name:'_deleteadmin',requirements: ['id' => '[1-9]\d*'])]
    public function panierDelete (EntityManagerInterface $em, User $id) : Response
    {
        $usersRepo = $em->getRepository(User::class);
        $user = $usersRepo->find($id);

        $em->remove($user);
        $em->flush();

        return $this->redirectToRoute("app_sandbox_listadmin");
    }
}
