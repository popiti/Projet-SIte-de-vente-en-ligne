<?php

namespace App\Entity;

use App\Repository\CommandeRepository;
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

    #[ORM\OneToMany(mappedBy: 'commandId', targetEntity: CommandeArticle::class)]
    private Collection $articleId;





    public function __construct()
    {
        $this->articleId = new ArrayCollection();
        $this->panier = new ArrayCollection();
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
     * @return Collection<int, CommandeArticle>
     */
    public function getArticleId(): Collection
    {
        return $this->articleId;
    }

    public function addArticleId(CommandeArticle $articleId): self
    {
        if (!$this->articleId->contains($articleId)) {
            $this->articleId->add($articleId);
            $articleId->setCommandId($this);
        }

        return $this;
    }

    public function removeArticleId(CommandeArticle $articleId): self
    {
        if ($this->articleId->removeElement($articleId)) {
            // set the owning side to null (unless already changed)
            if ($articleId->getCommandId() === $this) {
                $articleId->setCommandId(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Panier>
     */
    public function getPanier(): Collection
    {
        return $this->panier;
    }

    public function addPanier(Panier $panier): self
    {
        if (!$this->panier->contains($panier)) {
            $this->panier->add($panier);
            $panier->setCommande($this);
        }

        return $this;
    }

    public function removePanier(Panier $panier): self
    {
        if ($this->panier->removeElement($panier)) {
            // set the owning side to null (unless already changed)
            if ($panier->getCommande() === $this) {
                $panier->setCommande(null);
            }
        }

        return $this;
    }

}
