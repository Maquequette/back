<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\ChallengeRepository;
use App\Trait\Active;
use App\Trait\Timestamp;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints\NotBlank;

#[ORM\Entity(repositoryClass: ChallengeRepository::class)]
#[ORM\HasLifecycleCallbacks]
#[ApiResource]
class Challenge
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'challenges')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $author = null;

    #[ORM\Column(length: 255)]
    #[NotBlank]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT)]
    #[NotBlank]
    private ?string $description = null;

    #[ORM\ManyToOne(inversedBy: 'challenges')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Difficulty $difficulty = null;

    #[ORM\ManyToOne(inversedBy: 'challenges')]
    #[ORM\JoinColumn(nullable: false)]
    private ?ChallengeType $type = null;

    #[ORM\OneToMany(mappedBy: 'challenge', targetEntity: Ressource::class)]
    private Collection $ressources;

    #[ORM\Column]
    private ?bool $allowed = true;

    #[ORM\OneToMany(mappedBy: 'challenge', targetEntity: Solution::class, orphanRemoval: true)]
    private Collection $solutions;

    #[ORM\OneToMany(mappedBy: 'challenge', targetEntity: ChallengeLike::class, orphanRemoval: true)]
    private Collection $challengeLikes;

    public function __construct()
    {
        $this->ressources = new ArrayCollection();
        $this->solutions = new ArrayCollection();
        $this->challengeLikes = new ArrayCollection();
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

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getDifficulty(): ?Difficulty
    {
        return $this->difficulty;
    }

    public function setDifficulty(?Difficulty $difficulty): self
    {
        $this->difficulty = $difficulty;

        return $this;
    }

    public function getType(): ?ChallengeType
    {
        return $this->type;
    }

    public function setType(?ChallengeType $type): self
    {
        $this->type = $type;

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
            $ressource->setChallenge($this);
        }

        return $this;
    }

    public function removeRessource(Ressource $ressource): self
    {
        if ($this->ressources->removeElement($ressource)) {
            // set the owning side to null (unless already changed)
            if ($ressource->getChallenge() === $this) {
                $ressource->setChallenge(null);
            }
        }

        return $this;
    }

    public function isAllowed(): ?bool
    {
        return $this->allowed;
    }

    public function setAllowed(bool $allowed): self
    {
        $this->allowed = $allowed;

        return $this;
    }

    /**
     * @return Collection<int, Solution>
     */
    public function getSolutions(): Collection
    {
        return $this->solutions;
    }

    public function addSolution(Solution $solution): self
    {
        if (!$this->solutions->contains($solution)) {
            $this->solutions->add($solution);
            $solution->setChallenge($this);
        }

        return $this;
    }

    public function removeSolution(Solution $solution): self
    {
        if ($this->solutions->removeElement($solution)) {
            // set the owning side to null (unless already changed)
            if ($solution->getChallenge() === $this) {
                $solution->setChallenge(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, ChallengeLike>
     */
    public function getChallengeLikes(): Collection
    {
        return $this->challengeLikes;
    }

    public function addChallengeLike(ChallengeLike $challengeLike): self
    {
        if (!$this->challengeLikes->contains($challengeLike)) {
            $this->challengeLikes->add($challengeLike);
            $challengeLike->setChallenge($this);
        }

        return $this;
    }

    public function removeChallengeLike(ChallengeLike $challengeLike): self
    {
        if ($this->challengeLikes->removeElement($challengeLike)) {
            // set the owning side to null (unless already changed)
            if ($challengeLike->getChallenge() === $this) {
                $challengeLike->setChallenge(null);
            }
        }

        return $this;
    }

}
