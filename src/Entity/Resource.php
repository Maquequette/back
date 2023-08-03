<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\ResourceRepository;
use App\Trait\Active;
use App\Trait\Timestamp;
use Doctrine\ORM\Mapping as ORM;
use phpDocumentor\Reflection\Types\Self_;
use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\Validator\Constraints\NotBlank;

 #[ORM\Entity(repositoryClass: ResourceRepository::class)]
#[ORM\HasLifecycleCallbacks]
#[ApiResource]
class Resource
{
    //type of the resource (ex: file, image, video ...)
    public const TYPE_FILE = 'file';
    public const TYPE_IMAGE = 'image';
    public const TYPE_URL = 'url';

    public const TYPES = [
        self::TYPE_FILE,
        self::TYPE_IMAGE,
        self::TYPE_URL
    ];

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $label = null;

    // value of the resource (ex: file URL, ...)
    #[ORM\Column(length: 255)]
    #[NotBlank]
    private ?string $value = null;

    #[ORM\Column(length: 255)]
    #[Choice(Resource::TYPES)]
    private ?string $type = null;

    #[ORM\ManyToOne(inversedBy: 'resources')]
    #[ORM\JoinColumn(nullable: false)]
    private ?PolymorphicEntity $target = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(?string $label): self
    {
        $this->label = $label;

        return $this;
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function setValue(string $value): self
    {
        $this->value = $value;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getTarget(): ?PolymorphicEntity
    {
        return $this->target;
    }

    public function setTarget(?PolymorphicEntity $target): self
    {
        $this->target = $target;

        return $this;
    }

    use Active;
    use Timestamp;
}
