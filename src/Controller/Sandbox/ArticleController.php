<?php

namespace App\Controller\Sandbox;

use App\Entity\Article;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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
        return $this->render('article/index.html.twig',$args );
    }

}
