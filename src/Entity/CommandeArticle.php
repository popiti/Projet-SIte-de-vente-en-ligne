<?php

namespace App\Entity;

use App\Repository\CommandeArticleRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CommandeArticleRepository::class)]
class CommandeArticle
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(nullable: true)]
    private ?int $quantite = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Commande $commandId = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Article $articleId = null;

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

    public function getCommandId(): ?Commande
    {
        return $this->commandId;
    }

    public function setCommandId(?Commande $commandId): self
    {
        $this->commandId = $commandId;

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
}
