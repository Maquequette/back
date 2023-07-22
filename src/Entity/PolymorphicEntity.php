<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use App\Repository\PolymorphicEntityRepository;
use App\Trait\Comments;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PolymorphicEntityRepository::class)]
#[ORM\InheritanceType("JOINED")]
#[ORM\Table(options: ['comment' => '1 => Challenge | 2 => Solution | 3 => Comment'])]
#[ORM\DiscriminatorColumn(name: "type", type: "smallint")]
#[ORM\DiscriminatorMap([
    1 => Challenge::class,
    2 => Solution::class,
    3 => Comment::class
])]
#[ApiResource(
    operations: [
        /*new Get(
            uriTemplate: '/commentable/{id}/comments',
            normalizationContext: ['groups' => ['Comments'], 'enable_max_depth' => true],
        #filters: [],
        ),*/
        /*new GetCollection(
            uriTemplate: '/comments/from',
            requirements: ['id' => '\d+'],
            controller: PlaceholderAction::class,
            normalizationContext: [
                'groups' => ['Challenge', 'Comment'],
                'enable_max_depth' => true
            ],
            filters: ['entity.exists_filter'],
            name: 'GetEntityComments'
        )*/
    ]
),
    Get
]
abstract class PolymorphicEntity
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    use Comments {
        Comments::__construct as private __CommentsConstruct;
    }

    public function __construct()
    {
        $this->__CommentsConstruct();
    }
}
