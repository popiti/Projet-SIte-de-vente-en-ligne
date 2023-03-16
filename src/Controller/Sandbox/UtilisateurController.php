<?php

namespace App\Controller\Sandbox;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UtilisateurController extends AbstractController
{
    #[Route('/sandbox/utilisateur', name: 'app_sandbox_utilisateur')]
    public function index(): Response
    {
        $args = array();
        return $this->render('/Layouts/template.html.twig', $args);
    }
}
