<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use App\Repository\TagRepository;
use App\State\ActiveOnlyProvider;
use App\Trait\Active;
use App\Trait\Timestamp;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints\NotBlank;

#[ORM\Entity(repositoryClass: TagRepository::class)]
#[ORM\HasLifecycleCallbacks]
#[ApiResource(
    normalizationContext: ['groups' => ['TagFamily', 'TagFamilies', 'Challenge', 'Challenges']],
    provider: ActiveOnlyProvider::class),
    GetCollection(normalizationContext: ['groups' => ['Tags']]),
    Get(normalizationContext: ['groups' => ['Tag']]),
    Post, Put, Delete, Patch
]
class Tag
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['Tag', 'Tags', 'TagFamily', 'TagFamilies', 'Challenge', 'Challenges'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[NotBlank]
    #[Groups(['Tag', 'Tags', 'TagFamily', 'TagFamilies', 'Challenge', 'Challenges'])]
    private ?string $label = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['Tag', 'TagFamily', 'TagFamilies', 'Challenge', 'Challenges'])]
    private ?Color $color = null;

    #[ORM\ManyToOne(inversedBy: 'tags')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['Tag'])]
    private ?TagFamily $family = null;

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

    public function getColor(): ?Color
    {
        return $this->color;
    }

    public function setColor(?Color $color): self
    {
        $this->color = $color;

        return $this;
    }

    public function getFamily(): ?TagFamily
    {
        return $this->family;
    }

    public function setFamily(?TagFamily $family): self
    {
        $this->family = $family;

        return $this;
    }

    use Active;
    use Timestamp;
}
