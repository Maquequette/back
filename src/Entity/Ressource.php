<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\RessourceRepository;
use App\Trait\Active;
use App\Trait\Timestamp;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\Validator\Constraints\NotBlank;

#[ORM\Entity(repositoryClass: RessourceRepository::class)]
#[ORM\HasLifecycleCallbacks]
#[ApiResource]
class Ressource
{
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

    // type of the resource (ex: file, image, video ...)
    const TYPES = ['file', 'image'];

    #[ORM\Column(length: 255)]
    #[Choice(Ressource::TYPES)]
    private ?string $type = null;

    #[ORM\ManyToOne(inversedBy: 'ressources')]
    private ?Challenge $challenge = null;

    #[ORM\ManyToOne(inversedBy: 'ressources')]
    private ?Solution $solution = null;

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

    public function getChallenge(): ?Challenge
    {
        return $this->challenge;
    }

    public function setChallenge(?Challenge $challenge): self
    {
        $this->challenge = $challenge;

        return $this;
    }

    public function getSolution(): ?Solution
    {
        return $this->solution;
    }

    public function setSolution(?Solution $solution): self
    {
        $this->solution = $solution;

        return $this;
    }

    use Active;
    use Timestamp;
}
