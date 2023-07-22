<?php

namespace App\Trait;

use App\Entity\Like;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

trait Likes
{

    #[ORM\OneToMany(mappedBy: 'target', targetEntity: Like::class, orphanRemoval: true)]
    private Collection $likes;

    #[Groups(['Challenge', 'Challenges'])]
    private int $likesCount;

    #[Groups(['Challenge', 'Challenges'])]
    private bool $isLiked = false;

    public function __construct()
    {
        $this->likes = new ArrayCollection();
    }

    /**
     * @return Collection<int, Like>
     */
    public function getLikes(): Collection
    {
        return $this->likes;
    }

    public function addLike(Like $like): self
    {
        if (!$this->likes->contains($like)) {
            $this->likes->add($like);
            $like->setTarget($this);
        }

        return $this;
    }

    public function removeLike(Like $like): self
    {
        if ($this->likes->removeElement($like)) {
            // set the owning side to null (unless already changed)
            if ($like->getTarget() === $this) {
                $like->setTarget(null);
            }
        }

        return $this;
    }

    public function getLikesCount(): int
    {
        return $this->getLikes()->count();
    }

    public function getIsLiked(): bool
    {
        return $this->isLiked;
    }

    public function setIsLiked(bool $isLiked): self
    {
        $this->isLiked = $isLiked;
        return $this;
    }
}