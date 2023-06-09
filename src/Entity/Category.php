<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use App\Repository\CategoryRepository;
use App\Trait\Active;
use App\Trait\Timestamp;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints\NotBlank;

#[ORM\Entity(repositoryClass: CategoryRepository::class)]
#[ORM\HasLifecycleCallbacks]
#[ApiResource,
    GetCollection(normalizationContext: ['groups' => ['Categories']]),
    Get(normalizationContext: ['groups' => ['Category']]),
    Post, Put, Delete, Patch
]
class Category
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['Category', 'Categories'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[NotBlank]
    #[Groups(['Category', 'Categories'])]
    private ?string $label = null;

    #[ORM\Column(type: Types::TEXT)]
    #[NotBlank]
    #[Groups(['Category', 'Categories'])]
    private ?string $description = null;

    #[ORM\OneToMany(mappedBy: 'category', targetEntity: TagFamily::class)]
    private Collection $tagFamilies;

    public function __construct()
    {
        $this->tagFamilies = new ArrayCollection();
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

    use Active;
    use Timestamp;

    /**
     * @return Collection<int, TagFamily>
     */
    public function getTagFamilies(): Collection
    {
        return $this->tagFamilies;
    }

    public function addTagFamily(TagFamily $tagFamily): self
    {
        if (!$this->tagFamilies->contains($tagFamily)) {
            $this->tagFamilies->add($tagFamily);
            $tagFamily->setCategory($this);
        }

        return $this;
    }

    public function removeTagFamily(TagFamily $tagFamily): self
    {
        if ($this->tagFamilies->removeElement($tagFamily)) {
            // set the owning side to null (unless already changed)
            if ($tagFamily->getCategory() === $this) {
                $tagFamily->setCategory(null);
            }
        }

        return $this;
    }
}
