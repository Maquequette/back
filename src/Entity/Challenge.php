<?php

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use App\Repository\ChallengeRepository;
use App\Trait\Active;
use App\Trait\Timestamp;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints\NotBlank;

#[ORM\Entity(repositoryClass: ChallengeRepository::class)]
#[ORM\HasLifecycleCallbacks]
#[ApiResource,
    GetCollection(normalizationContext: ['groups' => ['Challenges', 'Difficulty']]),
    Get(normalizationContext: ['groups' => ['Challenge']]),
    Post, Put, Delete, Patch
]
#[ApiFilter(OrderFilter::class, properties: ['createdAt', 'difficulty.sortLevel'], arguments: ['orderParameterName' => 'order'])]
#[ApiFilter(SearchFilter::class, properties: ['title' => 'partial', 'description' => 'partial'])]
class Challenge
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['Challenge', 'Challenges'])]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'challenges')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['Challenge', 'Challenges'])]
    private ?User $author = null;

    #[ORM\Column(length: 255)]
    #[NotBlank]
    #[Groups(['Challenge', 'Challenges'])]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT)]
    #[NotBlank]
    #[Groups(['Challenge', 'Challenges'])]
    private ?string $description = null;

    #[ORM\ManyToOne(inversedBy: 'challenges')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['Challenge', 'Challenges'])]
    private ?Difficulty $difficulty = null;

    #[ORM\ManyToOne(inversedBy: 'challenges')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['Challenge', 'Challenges'])]
    private ?ChallengeType $type = null;

    #[ORM\OneToMany(mappedBy: 'challenge', targetEntity: Ressource::class)]
    #[Groups(['Challenge'])]
    private Collection $ressources;

    #[ORM\Column]
    private ?bool $allowed = true;

    #[ORM\OneToMany(mappedBy: 'challenge', targetEntity: Solution::class, orphanRemoval: true)]
    #[Groups(['Challenge'])]
    private Collection $solutions;

    #[ORM\OneToMany(mappedBy: 'challenge', targetEntity: ChallengeLike::class, orphanRemoval: true)]
    #[Groups(['Challenge', 'Challenges'])]
    private Collection $challengeLikes;

    #[ORM\ManyToMany(targetEntity: Tag::class)]
    #[Groups(['Challenge', 'Challenges'])]
    private Collection $tags;

    public function __construct()
    {
        $this->ressources = new ArrayCollection();
        $this->solutions = new ArrayCollection();
        $this->challengeLikes = new ArrayCollection();
        $this->tags = new ArrayCollection();
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

    public function isAllowed(): ?bool
    {
        return $this->allowed;
    }

    public function setAllowed(bool $allowed): self
    {
        $this->allowed = $allowed;

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

    /**
     * @return Collection<int, Tag>
     */
    public function getTags(): Collection
    {
        return $this->tags;
    }

    public function addTag(Tag $tag): self
    {
        if (!$this->tags->contains($tag)) {
            $this->tags->add($tag);
        }

        return $this;
    }

    public function removeTag(Tag $tag): self
    {
        $this->tags->removeElement($tag);

        return $this;
    }

}
