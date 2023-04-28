<?php

namespace App\Controller\Sandbox;

use App\Entity\Article;
use App\Entity\Panier;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class LoginController extends AbstractController
{
    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }
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
    #[Route(path: '/logoutmsg', name: 'app_logoutmsg')]
    public function msgLogout(): Response
    {
        $this->addFlash('info', "Vous vous êtes déconnecté");
        return $this->redirectToRoute('app_home');
    }

    public function menu(EntityManagerInterface $em) : Response
    {
        $panierRepo = $em->getRepository(Panier::class);
        $articlesRepo = $em->getRepository(Article::class);
        $paniers = $panierRepo->findBy(['userId'=>$this->getUser()]);
        $total = 0;
        foreach($paniers as $panier)
        {
            $articles = $articlesRepo->find($panier->getArticleId());
            if($articles==$panier->getArticleId())
            {
                $total +=1;
            }
        }

        $args = array(
            'nb'=>$total,
            );

        return $this->render("Layouts/menu.html.twig",$args);
    }
}
