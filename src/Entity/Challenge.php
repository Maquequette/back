<?php

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\NumericFilter;
use ApiPlatform\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use App\Controller\Challenge\ChallengeDislikeController;
use App\Controller\Challenge\ChallengeLikeController;
use App\Controller\Challenge\CreateChallengeController;
use App\Filter\MultiSearch;
use App\Repository\ChallengeRepository;
use App\State\IsLikedProvider;
use App\Trait\Active;
use App\Trait\Comments;
use App\Trait\Likes;
use App\Trait\Resources;
use App\Trait\Timestamp;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\OpenApi\Model;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints\NotBlank;

#[ORM\Entity(repositoryClass: ChallengeRepository::class)]
#[ORM\HasLifecycleCallbacks]
#[ApiResource(
    operations: [
        new Post(
            inputFormats: ['multipart' => ['multipart/form-data']],
            controller: CreateChallengeController::class,
            openapi: new Model\Operation(
                requestBody: new Model\RequestBody(
                    content: new \ArrayObject([
                        'multipart/form-data' => [
                            'schema' => [
                                'type' => 'object',
                                'properties' => [
                                    'title' => ['type' => 'string'],
                                    'description' => ['type' => 'string'],
                                    'difficulty' => ['type' => 'int'],
                                    'type' => ['type' => 'int'],
                                    'ressources' => ['type' => 'array'],
                                    'tags' => ['type' => 'array']
                                ]
                            ]
                        ]
                    ])
                )
            ),
            denormalizationContext: ['groups' => ['Challenge:POST']],
            deserialize: false
        ),
        new Post(
            uriTemplate: '/challenges/{id}/like',
            requirements: ['id' => '\d+'],
            controller: ChallengeLikeController::class,
            deserialize: false,
            name: 'ChallengeLike'
        ),
        new Delete(
            uriTemplate: '/challenges/{id}/like',
            requirements: ['id' => '\d+'],
            controller: ChallengeDislikeController::class,
            deserialize: false,
            name: 'ChallengeDislike'
        )
    ],
    provider: IsLikedProvider::class,
),
    GetCollection(normalizationContext: ['groups' => ['Challenges', 'Difficulty']]),
    Get(normalizationContext: ['groups' => ['Challenge']]),
    Put, Delete, Patch
]
#[ApiFilter(OrderFilter::class, properties: ['createdAt', 'difficulty.sortLevel'], arguments: ['orderParameterName' => 'order'])]
#[ApiFilter(MultiSearch::class, properties: ['title', 'description'])]
#[ApiFilter(NumericFilter::class, properties: ['type.category.id', 'difficulty.id', 'tags.id'])]
class Challenge extends PolymorphicEntity
{

    #[ORM\ManyToOne(inversedBy: 'challenges')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['Challenge', 'Challenges'])]
    private ?User $author = null;

    #[ORM\Column(length: 255)]
    #[NotBlank]
    #[Groups(['Challenge', 'Challenges', 'Challenge:POST'])]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT)]
    #[NotBlank]
    #[Groups(['Challenge', 'Challenges', 'Challenge:POST'])]
    private ?string $description = null;

    #[ORM\ManyToOne(inversedBy: 'challenges')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['Challenge', 'Challenges', 'Challenge:POST'])]
    #[ApiProperty(writableLink: false)]
    private ?Difficulty $difficulty = null;

    #[ORM\ManyToOne(inversedBy: 'challenges')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['Challenge', 'Challenges', 'Challenge:POST'])]
    private ?ChallengeType $type = null;

    #[ORM\Column]
    private ?bool $allowed = true;

    #[ORM\OneToMany(mappedBy: 'challenge', targetEntity: Solution::class, orphanRemoval: true)]
    #[Groups(['Challenge'])]
    private Collection $solutions;

    #[ORM\ManyToMany(targetEntity: Tag::class)]
    #[Groups(['Challenge', 'Challenges', 'Challenge:POST'])]
    private Collection $tags;

    use Likes {
        Likes::__construct as private __LikesConstruct;
    }

    use Resources {
        Resources::__construct as private __ResourcesConstruct;
    }

    public function __construct()
    {
        parent::__construct();
        $this->__LikesConstruct();
        $this->__ResourcesConstruct();
        $this->solutions = new ArrayCollection();
        $this->tags = new ArrayCollection();
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
