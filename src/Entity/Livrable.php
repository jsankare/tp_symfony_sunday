<?php

namespace App\Entity;

use App\Repository\LivrableRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LivrableRepository::class)]
class Livrable
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $goal = null;

    #[ORM\Column]
    private ?\DateTime $goalDate = null;

    #[ORM\Column]
    private ?\DateTime $startDate = null;

    #[ORM\ManyToOne(inversedBy: 'livrable')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Projet $projet = null;

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

    public function getGoal(): ?string
    {
        return $this->goal;
    }

    public function setGoal(string $goal): static
    {
        $this->goal = $goal;

        return $this;
    }

    public function getGoalDate(): ?\DateTime
    {
        return $this->goalDate;
    }

    public function setGoalDate(\DateTime $goalDate): static
    {
        $this->goalDate = $goalDate;

        return $this;
    }

    public function getStartDate(): ?\DateTime
    {
        return $this->startDate;
    }

    public function setStartDate(\DateTime $startDate): static
    {
        $this->startDate = $startDate;

        return $this;
    }

    public function getProjet(): ?Projet
    {
        return $this->projet;
    }

    public function setProjet(?Projet $projet): static
    {
        $this->projet = $projet;

        return $this;
    }
}
