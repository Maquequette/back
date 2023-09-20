<?php

namespace App\Trait;

use App\Entity\Comment;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\MaxDepth;

trait Comments
{

    #[MaxDepth(1)]
    #[ORM\OneToMany(mappedBy: 'parent', targetEntity: Comment::class, orphanRemoval: true)]
    #[Groups([
        'Comment', 'Comments',
        'Solution'
    ])]
    #[ORM\OrderBy(['createdAt' => 'DESC'])]
    private Collection $comments;

    #[Groups([
        'Comment', 'Comments',
        'Challenge', 'Challenges',
        'Solution'
    ])]
    private int $commentsCount;

    public function __construct()
    {
        $this->comments = new ArrayCollection();
    }

    /**
     * @param int|null $limit
     * @return Collection|array
     */
    public function getComments(?int $limit = null): Collection | array
    {
        if ($limit === null) {
            return $this->comments;
        }

        return $this->comments->slice(0, $limit);
    }

    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setParent($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->contains($comment)) {
            $this->comments->removeElement($comment);
            // set the owning side to null (unless already changed)
            if ($comment->getParent() === $this) {
                $comment->setParent(null);
            }
        }

        return $this;
    }

    public function getCommentsCount(): int
    {
        return $this->getComments()->count();
    }
}