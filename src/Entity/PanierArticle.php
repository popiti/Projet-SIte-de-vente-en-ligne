<?php

namespace App\Entity;

use App\Repository\PanierArticleRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PanierArticleRepository::class)]
class PanierArticle
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(nullable: true)]
    private ?int $quantite = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Article $articleId = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Panier $panierId = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuantite(): ?int
    {
        return $this->quantite;
    }

    public function setQuantite(?int $quantite): self
    {
        $this->quantite = $quantite;

        return $this;
    }

    public function getArticleId(): ?Article
    {
        return $this->articleId;
    }

    public function setArticleId(?Article $articleId): self
    {
        $this->articleId = $articleId;

        return $this;
    }

    public function getPanierId(): ?Panier
    {
        return $this->panierId;
    }

    public function setPanierId(?Panier $panierId): self
    {
        $this->panierId = $panierId;

        return $this;
    }
}
