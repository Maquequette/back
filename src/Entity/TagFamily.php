<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use App\Repository\TagFamilyRepository;
use App\State\ActiveOnlyProvider;
use App\Trait\Active;
use App\Trait\Timestamp;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints\NotBlank;

#[ORM\Entity(repositoryClass: TagFamilyRepository::class)]
#[ORM\HasLifecycleCallbacks]
#[ApiResource(
    normalizationContext: ['groups' => ['TagFamily']],
    provider: ActiveOnlyProvider::class),
    GetCollection(normalizationContext: ['groups' => ['TagFamilies']]),
    Get, Post, Put, Delete, Patch
]
class TagFamily
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['TagFamily', 'TagFamilies'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[NotBlank]
    #[Groups(['TagFamily', 'TagFamilies'])]
    private ?string $label = null;

    #[ORM\OneToMany(mappedBy: 'family', targetEntity: Tag::class)]
    #[Groups(['TagFamily', 'TagFamilies'])]
    private Collection $tags;

    public function __construct()
    {
        $this->tags = new ArrayCollection();
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

    use Active;
    use Timestamp;

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
            $tag->setFamily($this);
        }

        return $this;
    }

    public function removeTag(Tag $tag): self
    {
        if ($this->tags->removeElement($tag)) {
            // set the owning side to null (unless already changed)
            if ($tag->getFamily() === $this) {
                $tag->setFamily(null);
            }
        }

        return $this;
    }
}
