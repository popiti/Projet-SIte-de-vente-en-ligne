<?php

namespace App\Controller\Sandbox;

use App\Entity\Article;
use App\Form\ArticleType;
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

#[Route('/sandbox', name: 'app_sandbox')]
class ArticleController extends AbstractController
{
    #[Route('/listarticle', name: '_listarticle')]
    public function listearticleAction(EntityManagerInterface $em, Request $request): Response
    {
        $articleRepo = $em->getRepository(Article::class);
        $panierRepo = $em->getRepository(Panier::class);
        $articles = $articleRepo->findAll();

        $forms = array();
        $views = array();
        ///$article=new Article();
        foreach ($articles as $article) {
            $panier = $panierRepo->findOneBy(['userId' => $this->getUser(), 'articleId' => $article->getId()]);
            if ($panier) {
                $min = 0 - $panier->getQuantite();
                ///dd(array_values(range($min,10)));
            } else {
                $min = 0;
            }
            $max = $article->getQuantite();
            $form = $this->createFormBuilder()
                ->add('qte', ChoiceType::class, array('choices' => range($min, $max),
                    'choice_label' => function ($value)
                    {
                        return $value;
                    },
                ))
                ->add('articleId', HiddenType::class, ['data' => $article->getId()])
                ->add('modifier', SubmitType::class)
                ->getForm();
            $forms[] = $form;
            $views[$article->getId()] = $form->createView();
        }
        foreach ($forms as $form)
        {
            $form->handleRequest($request);
            if ($form->isSubmitted())
            {
                $articless = $articleRepo->find($form->get('articleId')->getData());

                if($form->get('qte')->getData()>0)
                {
                   // dd($form->getData());
                    $paniertmp = $panierRepo->findOneBy(['articleId'=>$form->get('articleId')->getData(),'userId'=>$this->getUser()]);

                    if($paniertmp)
                    {
                        $total = $paniertmp->getQuantite()+$form->get('qte')->getData();
                        $paniertmp->setQuantite($total);
                        $em->persist($paniertmp);
                    }

                    else
                    {
                        $panier = new Panier();
                        $panier->setQuantite($form->get('qte')->getData());
                        $panier->setArticleId($articless);
                        $panier->setUserId($this->getUser());

                        $em->persist($panier);
                    }
                    $somArticle = $articless->getQuantite()-$form->get('qte')->getData();
                    $articless->setQuantite($somArticle);

                    $em->persist($articless);
                }

                elseif($form->get('qte')->getData()<0)
                {
                    $paniertmp = $panierRepo->findOneBy(['userId'=>$this->getUser(),'articleId'=>$form->get('articleId')->getData()]);
                    $articleqte = $articless->getQuantite()-$form->get('qte')->getData();
                    $total = $paniertmp->getQuantite()+$form->get('qte')->getData();

                    if($total == 0)
                    {
                        $em->remove($paniertmp);
                    }

                    else
                    {
                        $paniertmp->setQuantite($total);
                        $em->persist($paniertmp);
                    }
                    $articless->setQuantite($articleqte);

                    $em->persist($articless);
                }
                $em->flush();
                return $this->redirectToRoute('app_sandbox_listarticle');
            }
        }

        $args = array(
            'articles' => $articles,
            'views' => $views,
        );
        return $this->render('article/index.html.twig', $args);
    }

    #[Route('/addarticle', name: '_addarticle')]
    public function addarticleAction(EntityManagerInterface $em, Request $request,): Response
    {
        $article = new Article();
        $form = $this->createForm(ArticleType::class);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $article -> setNom(
                $form->get('nom')->getData()
            );
            $article->setQuantite(
              $form->get('quantite')->getData()
            );
            $article->setPrix(
                $form->get('prix')->getData()
            );
            dump($article);
            $em->persist($article);
            $em->flush();
            return $this->redirectToRoute('app_sandbox_listarticle');
        }
        $args = array(
            'addArticle'=>$form->createView(),
        );
        return $this->render('article/addarticle.html.twig',$args);
    }


}
