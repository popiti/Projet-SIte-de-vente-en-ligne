<?php

namespace App\Controller\Sandbox;

use App\Entity\Article;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Panier;
use App\Form\AddPanierType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;

class ArticleController extends AbstractController
{
    #[Route('/sandbox/article', name: 'app_sandbox_article')]
    public function index(EntityManagerInterface $em): Response
    {
        $articleRepo = $em->getRepository(Article::class);
        $articles = $articleRepo->findAll();
        $args = array(
            'articles'=> $articles,
        );
        return $this->render('article/client.html.twig',$args );
    }


    #[Route('/sandbox/article', name: 'app_sandbox_article')]
    public function listederoulanteAction(EntityManagerInterface $em, ManagerRegistry $doctrine, Request $request): Response
    {
        $articleRepo = $em->getRepository(Article::class);
        $panierRepo = $em->getRepository(Panier::class);
        $articles = $articleRepo->findAll();

        $forms = array();
        $views = array();
        ///$article=new Article();
        foreach ($articles as $article) {
            $panier = $panierRepo->findOneBy(['clientId' => $this->getUser(), 'articleId' => $article->getId()]);
            if ($panier) {
                $min = 0 - $panier->getQuantite();
                ///dd(array_values(range($min,10)));
            } else {
                $min = 0;
            }

            $max = $article->getQuantite();
            $form = $this->createFormBuilder()
                ->add('qte', ChoiceType::class, ['choices' => array_flip(range($min, $max))])
                ->add('articleId', HiddenType::class, ['data' => $article->getId()])
                ->add('modifier', SubmitType::class)
                ->getForm();
            $forms[] = $form;
            $views[$article->getId()] = $form->createView();
        }
        foreach ($forms as $form) {
            $form->handleRequest($request);
            if ($form->isSubmitted()) {
                dd($form->getData());
            }
        }

        $args = array(
            'articles' => $articles,
            'views' => $views,
        );


        return $this->render('article/index.html.twig', $args);
    }

}
