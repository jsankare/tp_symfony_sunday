<?php

namespace App\Entity;

use App\Repository\ProjetRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProjetRepository::class)]
class Projet
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    /**
     * @var Collection<int, livrable>
     */
    #[ORM\OneToMany(targetEntity: livrable::class, mappedBy: 'projet', orphanRemoval: true)]
    private Collection $livrable;

    #[ORM\ManyToOne(inversedBy: 'projects')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Client $client = null;

    public function __construct()
    {
        $this->livrable = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection<int, livrable>
     */
    public function getLivrable(): Collection
    {
        return $this->livrable;
    }

    public function addLivrable(livrable $livrable): static
    {
        if (!$this->livrable->contains($livrable)) {
            $this->livrable->add($livrable);
            $livrable->setProjet($this);
        }

        return $this;
    }

    public function removeLivrable(livrable $livrable): static
    {
        if ($this->livrable->removeElement($livrable)) {
            // set the owning side to null (unless already changed)
            if ($livrable->getProjet() === $this) {
                $livrable->setProjet(null);
            }
        }

        return $this;
    }

    public function getClient(): ?Client
    {
        return $this->client;
    }

    public function setClient(?Client $client): static
    {
        $this->client = $client;

        return $this;
    }
}
