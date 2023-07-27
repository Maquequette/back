<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\SolutionRepository;
use App\Trait\Active;
use App\Trait\Likes;
use App\Trait\Resources;
use App\Trait\Timestamp;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SolutionRepository::class)]
#[ORM\HasLifecycleCallbacks]
#[ApiResource]
class Solution extends PolymorphicEntity
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'solutions')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $author = null;

    #[ORM\ManyToOne(inversedBy: 'solutions')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Challenge $challenge = null;

    #[ORM\Column]
    private ?bool $visible = null;

    use Likes {
        Likes::__construct as private __LikesConstruct;
    }

    use Resources {
        Resources::__construct as private __ResourcesConstruct;
    }

    public function __construct()
    {
        parent::__construct();
        $this->__LikesConstruct();
        $this->__ResourcesConstruct();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getChallenge(): ?Challenge
    {
        return $this->challenge;
    }

    public function setChallenge(?Challenge $challenge): self
    {
        $this->challenge = $challenge;

        return $this;
    }

    public function isVisible(): ?bool
    {
        return $this->visible;
    }

    public function setVisible(bool $visible): self
    {
        $this->visible = $visible;

        return $this;
    }

    use Active;
    use Timestamp;
}
