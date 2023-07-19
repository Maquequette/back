<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
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
#[ApiResource]
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
