<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use App\Repository\DifficultyRepository;
use App\State\ActiveOnlyProvider;
use App\Trait\Active;
use App\Trait\Timestamp;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints\NotBlank;

#[ORM\Entity(repositoryClass: DifficultyRepository::class)]
#[ORM\HasLifecycleCallbacks]
#[ApiResource(
    normalizationContext: ['groups' => ['Challenge', 'Challenges']],
    provider: ActiveOnlyProvider::class),
    GetCollection(normalizationContext: ['groups' => ['Difficulties']]),
    Get(normalizationContext: ['groups' => ['Difficulty']]),
    Post, Put, Delete, Patch
]
class Difficulty
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['Difficulty', 'Difficulties'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[NotBlank]
    #[Groups(['Difficulty', 'Difficulties', 'Challenge', 'Challenges'])]
    private ?string $label = null;

    #[ORM\Column(type: Types::TEXT)]
    #[NotBlank]
    #[Groups(['Difficulty', 'Difficulties', 'Challenge', 'Challenges'])]
    private ?string $description = null;

    #[ORM\Column(type: Types::SMALLINT)]
    #[Groups(['Difficulty', 'Difficulties', 'Challenge', 'Challenges'])]
    private ?int $sortLevel = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['Difficulty', 'Difficulties', 'Challenge', 'Challenges'])]
    private ?Color $color = null;

    #[ORM\OneToMany(mappedBy: 'difficulty', targetEntity: Challenge::class, orphanRemoval: true)]
    private Collection $challenges;

    public function __construct()
    {
        $this->challenges = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(string $label): self
    {
        $this->label = $label;

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

    public function getSortLevel(): ?int
    {
        return $this->sortLevel;
    }

    public function setSortLevel(int $sortLevel): self
    {
        $this->sortLevel = $sortLevel;

        return $this;
    }

    public function getColor(): ?Color
    {
        return $this->color;
    }

    public function setColor(?Color $color): self
    {
        $this->color = $color;

        return $this;
    }

    use Active;
    use Timestamp;

    /**
     * @return Collection<int, Challenge>
     */
    public function getChallenges(): Collection
    {
        return $this->challenges;
    }

    public function addChallenge(Challenge $challenge): self
    {
        if (!$this->challenges->contains($challenge)) {
            $this->challenges->add($challenge);
            $challenge->setDifficulty($this);
        }

        return $this;
    }

    public function removeChallenge(Challenge $challenge): self
    {
        if ($this->challenges->removeElement($challenge)) {
            // set the owning side to null (unless already changed)
            if ($challenge->getDifficulty() === $this) {
                $challenge->setDifficulty(null);
            }
        }

        return $this;
    }
}
