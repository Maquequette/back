<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use App\Controller\Comment\CommentDislikeController;
use App\Controller\Comment\CommentLikeController;
use App\Controller\Comment\CreateCommentController;
use App\Filter\OrderByLikesCount;
use App\Repository\CommentRepository;
use App\State\IsLikedProvider;
use App\Trait\Active;
use App\Trait\Likes;
use App\Trait\Timestamp;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints\NotBlank;

#[ORM\Entity(repositoryClass: CommentRepository::class)]
#[ORM\HasLifecycleCallbacks]
#[ApiResource(
    operations: [
        new Post(
            controller: CreateCommentController::class,
        ),
        new Post(
            uriTemplate: '/comments/{id}/like',
            requirements: ['id' => '\d+'],
            controller: CommentLikeController::class,
            normalizationContext: ['groups' => ['Comment']],
            deserialize: false,
            name: 'CommentLike'
        ),
        new Delete(
            uriTemplate: '/comments/{id}/like',
            requirements: ['id' => '\d+'],
            controller: CommentDislikeController::class,
            deserialize: false,
            name: 'CommentDislike'
        )
    ],
    provider: IsLikedProvider::class
),
    GetCollection(
        uriTemplate: '/comments/from',
        paginationItemsPerPage: 10,
        paginationPartial: true,
        normalizationContext: ['groups' => ['Comments'], 'enable_max_depth' => true],
        filters: ['comments.from', 'comments.sort', OrderByLikesCount::class],
        name: 'GetCommentsFrom'
    ),
    Get(normalizationContext: ['groups' => ['Comment']]),
    Delete
]
class Comment extends PolymorphicEntity
{

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups([
        'Comment', 'Comments',
        'Challenge', 'Challenges'
    ])]
    private ?User $author = null;

    #[ORM\Column(type: Types::TEXT)]
    #[NotBlank]
    #[Groups([
        'Comment', 'Comments',
        'Challenge', 'Challenges'
    ])]
    private ?string $content = null;

    #[ORM\ManyToOne(targetEntity: PolymorphicEntity::class, inversedBy: 'comments')]
    #[ORM\JoinColumn(nullable: false)]
    private ?PolymorphicEntity $parent = null;

    use Likes {
        Likes::__construct as private __LikesConstruct;
    }

    public function __construct()
    {
        parent::__construct();
        $this->__LikesConstruct();
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

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getParent(): ?PolymorphicEntity
    {
        return $this->parent;
    }

    public function setParent(?PolymorphicEntity $parent): PolymorphicEntity
    {
        $this->parent = $parent;

        return $this;
    }

    use Active;
    use Timestamp;
}
