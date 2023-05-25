<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use App\Repository\ColorRepository;
use App\Trait\Timestamp;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints\CssColor;

#[ORM\Entity(repositoryClass: ColorRepository::class)]
#[ORM\HasLifecycleCallbacks]
#[ApiResource(
    normalizationContext: ['groups' => ['TagFamily', 'TagFamilies', 'Difficulty', 'Difficulties']]),
    GetCollection(normalizationContext: ['groups' => ['Colors']]),
    Get(normalizationContext: ['groups' => ['Color']]),
    Post, Put, Delete, Patch
]
class Color
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups( ['Color'] )]
    private ?int $id = null;

    #[ORM\Column(length: 12)]
    #[CssColor]
    #[Groups( ['Color', 'Colors', 'TagFamily', 'TagFamilies', 'Difficulty', 'Difficulties'] )]
    private ?string $code = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;

        return $this;
    }

    use Timestamp;
}
