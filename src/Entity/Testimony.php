<?php

namespace App\Entity;

use App\Repository\TestimonyRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TestimonyRepository::class)]
class Testimony
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * @var Collection<int, Client>
     */
    #[ORM\OneToMany(targetEntity: Client::class, mappedBy: 'testimony')]
    private Collection $author;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $content = null;

    public function __construct()
    {
        $this->author = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection<int, Client>
     */
    public function getAuthor(): Collection
    {
        return $this->author;
    }

    public function addAuthor(Client $author): static
    {
        if (!$this->author->contains($author)) {
            $this->author->add($author);
            $author->setTestimony($this);
        }

        return $this;
    }

    public function removeAuthor(Client $author): static
    {
        if ($this->author->removeElement($author)) {
            // set the owning side to null (unless already changed)
            if ($author->getTestimony() === $this) {
                $author->setTestimony(null);
            }
        }

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): static
    {
        $this->content = $content;

        return $this;
    }
}
