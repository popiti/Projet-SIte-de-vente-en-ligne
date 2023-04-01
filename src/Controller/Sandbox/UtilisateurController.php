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

class UtilisateurController extends AbstractController
{
    #[Route('profile/{id}', name: 'app_sandbox_profile', requirements: ['id' => '[1-9]\d*'])]
    public function profile(Request $request,UserPasswordHasherInterface $userPasswordHasher,UserAuthenticatorInterface
    $userAuthenticator,LoginAuthenticator $authenticator ,EntityManagerInterface $em,int $id): Response
    {
        $userRepo = $em->getRepository(User::class);
        if($this->getUser()->getId()!=$id)
        {
            $id = $this->getUser()->getId();
        }
        $users = $userRepo->find($id);
        $form = $this->createForm(MonProfile::class, $users);
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
            $this->addFlash('info', 'Les informations du compte ont été modifiées');
            // do anything else you need here, like send an email
            $role = $this->getUser()->getRoles();
            if($role[0]=="ROLE_SUPERADMIN")
            {
                return $this->redirectToRoute("app_home");
            }
            else
            {
                return $this->redirectToRoute("app_sandbox_listarticle");

            }
        }
        if (is_null($users))
    {
        //$this->addFlash('info', 'suppression film' . $id . 'reussie');
        throw new NotFoundHttpException('utilisateur' . $id . 'not found');
    }
        $args = array('id'=>$id,'registrationForm'=>$form->createView(),);
        return $this->render('/Sandbox/utilisateur/profile.html.twig', $args);
    }

    #[Route('sandbox/client',name: 'app_sandbox_listclient')]
    public function listClient(EntityManagerInterface $em) : Response
    {
        $usersRepo = $em->getRepository(User::class);
        $users = $usersRepo->findAll();

        $args=array(
        'users'=>$users
        );
        return $this->render('sandbox/utilisateur/client.html.twig',$args);
    }

    #[Route('superadmin/listadmin',name: 'app_sandbox_listadmin')]
    public function listAdmin(EntityManagerInterface $em) : Response
    {
        $usersRepo = $em->getRepository(User::class);
        $users = $usersRepo->findByRole("ROLE_ADMIN");

        $args=array(
            'users'=>$users
        );
        return $this->render('superadmin/admin.html.twig',$args);
    }

    #[Route('sandbox/client/modifclient/{id}', name: 'app_sandbox_modifclient', requirements : ['id' => '[1-9]\d*'])]
    public function modifclientAction(EntityManagerInterface $em,Request $request, UserPasswordHasherInterface $userPasswordHasher, int $id) : Response
    {
        $usersRepo = $em->getRepository(User::class);
        $users  = $usersRepo->find($id);
        $form = $this->createForm(MonProfile::class, $users);
        if(!in_array("ROLE_CLIENT",$users->getRoles()))
        {
            $this->addFlash('info','Vous n\'avez pas le droit d\'accéder à cette page');
            return $this->redirectToRoute('app_sandbox_listclient');
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
            return $this->redirectToRoute('app_sandbox_listclient');
        }
        if (is_null($users))
        {
            //$this->addFlash('info', 'suppression film' . $id . 'reussie');
            throw new NotFoundHttpException('utilisateur' . $id . 'not found');
        }
        $args = array(
            'id'=>$id,'registrationForm'=>$form->createView(),'nom'=>$users->getNom(),
        );
        return $this->render('Sandbox/utilisateur/modifclient.html.twig',$args);
    }

    #[Route('sandbox/client/deleteclient/{id}',name: 'app_sandbox_deleteclient', requirements: ['id'=>'[1-9]\d*'])]
    public function deleteclientAction(Request $request,EntityManagerInterface $em, int $id): Response
    {
        $usersRepo = $em->getRepository(User::class);
        $users = $usersRepo->find($id);
        if(in_array("ROLE_SUPERADMIN",$users->getRoles()))
        {
            $this->addFlash('info','Vous n\'avez pas le droit d\'accès');
            return $this->redirectToRoute('app_sandbox_listclient');
        }
        if(!strcmp($this->getUser()->getUserIdentifier(),$users->getLogin()))
        {
            $this->addFlash('info','Vous n\'avez pas le droit de vous supprimer');
            return $this->redirectToRoute('app_sandbox_listclient');
        }
        $em->remove($users);
        $em->flush();
        return $this->redirectToRoute('app_sandbox_listclient');
    }
}
