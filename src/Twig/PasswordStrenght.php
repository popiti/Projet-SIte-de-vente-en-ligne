<?php
//
//namespace App\Twig;
//
//use App\Entity\Panier;
//use Doctrine\ORM\EntityManagerInterface;
//use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
//use Twig\Extension\AbstractExtension;
//use Twig\TwigFunction;
//
//class PasswordStrenght extends AbstractExtension
//{
//    private $em;
//    public function __construct(EntityManagerInterface $em,TokenStorageInterface $tokenStorage){
//        $this->em=$em;
//        $this->user = $tokenStorage->getToken()->getUser();
//    }
//    public function getFunctions(): array
//    {
//        return [
//            new TwigFunction('nbpaniers',[$this,'getNbPanier'])
//        ];
//    }
//    public function getNbPanier(){
//        $panierRepo=$this->em->getRepository(Panier::class);
//        $paniers=$panierRepo->findBy(['clientId'=>$this->user]);
//        return count($paniers);
//    }
//}
