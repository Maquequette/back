<?php

namespace App\Trait;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

trait Active
{
    #[ORM\Column]
    #[Groups(["User:Me"])]
    private ?bool $active = true;

    public function isActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(bool $active): self
    {
        $this->active = $active;

        return $this;
    }
}