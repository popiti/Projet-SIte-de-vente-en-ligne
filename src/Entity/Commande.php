<?php

namespace App\Entity;

use App\Repository\CommandeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CommandeRepository::class)]
class Commande
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(nullable: true)]
    private ?int $totalPrice = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?user $userId = null;

    #[ORM\ManyToMany(targetEntity: Article::class, mappedBy: 'commandeId')]
    private Collection $articleId;

    public function __construct()
    {
        $this->articleId = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTotalPrice(): ?int
    {
        return $this->totalPrice;
    }

    public function setTotalPrice(?int $totalPrice): self
    {
        $this->totalPrice = $totalPrice;

        return $this;
    }

    public function getUserId(): ?utilisateur
    {
        return $this->userId;
    }

    public function setUserId(?utilisateur $userId): self
    {
        $this->userId = $userId;

        return $this;
    }

    /**
     * @return Collection<int, Article>
     */
    public function getArticleId(): Collection
    {
        return $this->articleId;
    }

    public function addArticleId(Article $articleId): self
    {
        if (!$this->articleId->contains($articleId)) {
            $this->articleId->add($articleId);
            $articleId->addCommandeId($this);
        }

        return $this;
    }

    public function removeArticleId(Article $articleId): self
    {
        if ($this->articleId->removeElement($articleId)) {
            $articleId->removeCommandeId($this);
        }

        return $this;
    }
}
