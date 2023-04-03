<?php

namespace App\Controller\Sandbox;

use App\Entity\Article;
use App\Entity\Panier;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/sandbox', name: 'panier')]
class PanierController extends AbstractController
{
    #[Route('/panier', name: '_list')]
    public function panierList(EntityManagerInterface $em): Response
    {
        $panierRepo = $em->getRepository(Panier::class);
        $paniers = $panierRepo->findBy(['userId'=>$this->getUser()]);
        if (!$paniers)
        {
            $this->addFlash('info','Le panier est vide');
        }

        $args = array('paniers'=> $paniers,);
        return $this->render('/panier/panier.html.twig', $args);
    }


    #[Route('/panier/delete/{id}',name:'_delete',requirements: ['id' => '[1-9]\d*'])]
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

        return $this->redirectToRoute("panier_list");
    }

    #[Route('/panier/vider',name:'_vider')]
    public function panierVider(EntityManagerInterface $em) : Response
    {
        $panierRepo = $em->getRepository(Panier::class);
        $articleRepo = $em->getRepository(Article::class);
        $paniers = $panierRepo->findBy(['userId'=>$this->getUser()]);

        foreach ($paniers as $panier) {
             $article = $articleRepo->find($panier->getArticleId());
             $somme = $article->getQuantite()+$panier->getQuantite();
             $article->setQuantite($somme);

             $em->persist($article);
             $em->remove($panier);
        }
        $em->flush();
        return $this->redirectToRoute('panier_list');
    }
    #[Route('/panier/valider',name:'_valider')]
    public function panierValider(EntityManagerInterface $em) : Response
    {
        $panierRepo = $em->getRepository(Panier::class);
        $paniers = $panierRepo->findBy(['userId'=>$this->getUser()]);

        foreach ($paniers as $panier) {
            $em->remove($panier);
            $this->addFlash('info', 'Félicitation ! Commande réussi');
        }
        $em->flush();

        return $this->redirectToRoute('panier_list');
    }
}
