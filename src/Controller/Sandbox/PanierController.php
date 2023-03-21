<?php

namespace App\Controller\Sandbox;

use App\Entity\Article;
use App\Entity\Panier;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PanierController extends AbstractController
{
    #[Route('/sandbox/panier', name: 'app_sandbox_panier')]
    public function index(EntityManagerInterface $em): Response
    {
        $panierRepo = $em->getRepository(Panier::class);
        $paniers = $panierRepo->findBy(['userId'=>$this->getUser()]);
        $args = array('paniers'=> $paniers,);
        return $this->render('/panier/panier.html.twig', $args);
    }


    #[Route('/sandbox/panier/delete/{id}',name:'panier_delete')]
    public function panierDelete (EntityManagerInterface $em, Panier $id) : Response
    {
        $panierRepo = $em->getRepository(Panier::class);
        $articleRepo = $em->getRepository(Article::class);
        $paniers = $panierRepo->find($id);
        $articles = $articleRepo->find($paniers->getArticleId());

        $quantite = $articles->getQuantite()+$paniers->getQuantite();
        $articles->setQuantite($quantite);

        $em->persist($articles);
        $em->remove($paniers);
        $em->flush();

        return $this->redirectToRoute("app_sandbox_panier");
    }
}
