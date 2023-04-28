<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Panier;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        return $this->render('home/home.html.twig', [
            'controller_name' => 'HomeController',
        ]);
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
