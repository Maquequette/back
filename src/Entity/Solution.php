<?php

namespace App\Entity;

use App\Repository\SolutionRepository;
use App\Trait\Active;
use App\Trait\Timestamp;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SolutionRepository::class)]
class Solution
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'solutions')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $author = null;

    #[ORM\ManyToOne(inversedBy: 'solutions')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Challenge $challenge = null;

    #[ORM\Column]
    private ?bool $visible = null;

    #[ORM\OneToMany(mappedBy: 'solution', targetEntity: Ressource::class)]
    private Collection $ressources;

    #[ORM\OneToMany(mappedBy: 'solution', targetEntity: SolutionLike::class, orphanRemoval: true)]
    private Collection $solutionLikes;

    public function __construct()
    {
        $this->ressources = new ArrayCollection();
        $this->solutionLikes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): self
    {
        $this->author = $author;

        return $this;
    }

    public function getChallenge(): ?Challenge
    {
        return $this->challenge;
    }

    public function setChallenge(?Challenge $challenge): self
    {
        $this->challenge = $challenge;

        return $this;
    }

    public function isVisible(): ?bool
    {
        return $this->visible;
    }

    public function setVisible(bool $visible): self
    {
        $this->visible = $visible;

        return $this;
    }

    use Active;
    use Timestamp;

    /**
     * @return Collection<int, Ressource>
     */
    public function getRessources(): Collection
    {
        return $this->ressources;
    }

    public function addRessource(Ressource $ressource): self
    {
        if (!$this->ressources->contains($ressource)) {
            $this->ressources->add($ressource);
            $ressource->setSolution($this);
        }

        return $this;
    }

    public function removeRessource(Ressource $ressource): self
    {
        if ($this->ressources->removeElement($ressource)) {
            // set the owning side to null (unless already changed)
            if ($ressource->getSolution() === $this) {
                $ressource->setSolution(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, SolutionLike>
     */
    public function getSolutionLikes(): Collection
    {
        return $this->solutionLikes;
    }

    public function addSolutionLike(SolutionLike $solutionLike): self
    {
        if (!$this->solutionLikes->contains($solutionLike)) {
            $this->solutionLikes->add($solutionLike);
            $solutionLike->setSolution($this);
        }

        return $this;
    }

    public function removeSolutionLike(SolutionLike $solutionLike): self
    {
        if ($this->solutionLikes->removeElement($solutionLike)) {
            // set the owning side to null (unless already changed)
            if ($solutionLike->getSolution() === $this) {
                $solutionLike->setSolution(null);
            }
        }

        return $this;
    }
}
