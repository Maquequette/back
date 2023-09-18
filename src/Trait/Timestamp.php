<?php

namespace App\Trait;

use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

trait Timestamp
{
    #[ORM\Column]
    #[Groups(['Challenge', 'Challenges', 'Comment', 'Comments'])]
    private ?DateTimeImmutable $createdAt = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['Challenge', 'Challenges', 'Comment', 'Comments'])]
    private ?DateTimeImmutable $updatedAt = null;

    public function getCreatedAt(): ?DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?DateTimeImmutable $timestamp): self
    {
        $this->createdAt = $timestamp;
        return $this;
    }

    public function getUpdatedAt(): ?DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?DateTimeImmutable $timestamp): self
    {
        $this->updatedAt = $timestamp;
        return $this;
    }

    #[ORM\PrePersist]
    public function setCreatedAtAutomatically(): void
    {
        if ($this->getCreatedAt() === null) {
            $this->setCreatedAt(new DateTimeImmutable());
        }
    }

    #[ORM\PreUpdate]
    public function setUpdatedAtAutomatically(): void
    {
        $this->setUpdatedAt(new DateTimeImmutable());
    }
}