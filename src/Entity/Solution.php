<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use App\Controller\Solution\SolutionDislikeController;
use App\Controller\Solution\CreateSolutionController;
use App\Controller\Solution\SolutionLikeController;
use App\Filter\OrderByLikesCount;
use App\Repository\SolutionRepository;
use App\Trait\Active;
use App\Trait\Likes;
use App\Trait\Resources;
use App\Trait\Timestamp;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\OpenApi\Model;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: SolutionRepository::class)]
#[ORM\HasLifecycleCallbacks]
#[ApiResource(
    operations: [
        new Post(
            uriTemplate: '/solutions/{id}/like',
            requirements: ['id' => '\d+'],
            controller: SolutionLikeController::class,
            normalizationContext: ['groups' => ['Solution']],
            deserialize: false,
            name: 'SolutionLike'
        ),
        new Delete(
            uriTemplate: '/solutions/{id}/like',
            requirements: ['id' => '\d+'],
            controller: SolutionDislikeController::class,
            deserialize: false,
            name: 'SolutionDislike'
        ),
        new Post(
            inputFormats: ['multipart' => ['multipart/form-data']],
            controller: CreateSolutionController::class,
            openapi: new Model\Operation(
                requestBody: new Model\RequestBody(
                    content: new \ArrayObject([
                        'multipart/form-data' => [
                            'schema' => [
                                'type' => 'object',
                                'properties' => [
                                    'recap' => ['type' => 'string'],
                                    'resources' => ['type' => 'array']
                                ]
                            ]
                        ]
                    ])
                )
            ),
            denormalizationContext: ['groups' => ['Challenge:POST']],
            deserialize: false
        ),
    ],
),
    GetCollection(
        uriTemplate: '/solutions/from',
        normalizationContext: ['groups' => ['Solutions', 'Challenge']],
        filters: ['solutions.from', 'solutions.sort', OrderByLikesCount::class],
        name: 'GetSolutionsFrom'
    ),
    Get(
        normalizationContext: ['groups' => ['Solution']]
    )
]
class Solution extends PolymorphicEntity
{

    #[ORM\ManyToOne(inversedBy: 'solutions')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['Challenge', 'Challenges','Solution', 'Solutions'])]
    private ?User $author = null;

    #[ORM\ManyToOne(inversedBy: 'solutions')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Challenge $challenge = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['Challenge', 'Challenges', 'Solution', 'Solutions'])]
    private ?string $recap = null;

    #[ORM\Column]
    private ?bool $visible = null;

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

    public function getRecap(): ?string
    {
        return $this->recap;
    }

    public function setRecap(?string $recap): self
    {
        $this->recap = $recap;

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
}
