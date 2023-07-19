<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\CommentRepository;
use App\Trait\Active;
use App\Trait\Comments;
use App\Trait\Likes;
use App\Trait\Timestamp;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints\NotBlank;

#[ORM\Entity(repositoryClass: CommentRepository::class)]
#[ORM\HasLifecycleCallbacks]
#[ApiResource(
    normalizationContext: ['groups' => ['Comment']]
)]
class Comment extends PolymorphicEntity
{

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['Comment', 'Challenge', 'Challenges'])]
    private ?User $author = null;

    #[ORM\Column(type: Types::TEXT)]
    #[NotBlank]
    #[Groups(['Comment', 'Challenge', 'Challenges'])]
    private ?string $content = null;

    #[ORM\ManyToOne(targetEntity: PolymorphicEntity::class, inversedBy: 'comments')]
    #[ORM\JoinColumn(nullable: false)]
    private ?PolymorphicEntity $parent = null;

    use Likes {
        Likes::__construct as private __LikesConstruct;
    }

    public function __construct()
    {
        parent::__construct();
        $this->__LikesConstruct();
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

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getParent(): ?PolymorphicEntity
    {
        return $this->parent;
    }

    public function setParent(?PolymorphicEntity $parent): PolymorphicEntity
    {
        $this->parent = $parent;

        return $this;
    }

    use Active;
    use Timestamp;
}
