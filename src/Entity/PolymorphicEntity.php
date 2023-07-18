<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\PolymorphicEntityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PolymorphicEntityRepository::class)]
#[ORM\InheritanceType("JOINED")]
#[ORM\DiscriminatorColumn(name: "type", type: "smallint", length: 8)]
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
}
