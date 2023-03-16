<?php

namespace App\Entity;

use App\Repository\PanierRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PanierRepository::class)]
class Panier
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?user $userId = null;

    #[ORM\ManyToMany(targetEntity: article::class, inversedBy: 'panierId')]
    private Collection $articleId;

    public function __construct()
    {
        $this->panierId = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserId(): ?utilisateur
    {
        return $this->userId;
    }

    public function setUserId(utilisateur $userId): self
    {
        $this->userId = $userId;

        return $this;
    }

    /**
     * @return Collection<int, article>
     */
    public function getPanierId(): Collection
    {
        return $this->panierId;
    }

    public function addPanierId(article $panierId): self
    {
        if (!$this->panierId->contains($panierId)) {
            $this->panierId->add($panierId);
        }

        return $this;
    }

    public function removePanierId(article $panierId): self
    {
        $this->panierId->removeElement($panierId);

        return $this;
    }
}
