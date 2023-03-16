<?php

namespace App\Entity;

use App\Repository\ArticleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ArticleRepository::class)]
class Article
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $nom = null;

    #[ORM\Column(nullable: true)]
    private ?int $quantite = null;

    #[ORM\Column(nullable: true)]
    private ?int $prix = null;

    #[ORM\ManyToMany(targetEntity: Commande::class, inversedBy: 'articleId')]
    private Collection $commandeId;

    #[ORM\ManyToMany(targetEntity: Panier::class, mappedBy: 'panierId')]
    private Collection $panierId;

    public function __construct()
    {
        $this->commandeId = new ArrayCollection();
        $this->panierId = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(?string $nom): self
    {
        $this->nom = $nom;

        return $this;
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

    public function getPrix(): ?int
    {
        return $this->prix;
    }

    public function setPrix(?int $prix): self
    {
        $this->prix = $prix;

        return $this;
    }

    /**
     * @return Collection<int, Commande>
     */
    public function getCommandeId(): Collection
    {
        return $this->commandeId;
    }

    public function addCommandeId(Commande $commandeId): self
    {
        if (!$this->commandeId->contains($commandeId)) {
            $this->commandeId->add($commandeId);
        }

        return $this;
    }

    public function removeCommandeId(Commande $commandeId): self
    {
        $this->commandeId->removeElement($commandeId);

        return $this;
    }

    /**
     * @return Collection<int, Panier>
     */
    public function getPanierId(): Collection
    {
        return $this->panierId;
    }

    public function addPanierId(Panier $panierId): self
    {
        if (!$this->panierId->contains($panierId)) {
            $this->panierId->add($panierId);
            $panierId->addPanierId($this);
        }

        return $this;
    }

    public function removePanierId(Panier $panierId): self
    {
        if ($this->panierId->removeElement($panierId)) {
            $panierId->removePanierId($this);
        }

        return $this;
    }
}
