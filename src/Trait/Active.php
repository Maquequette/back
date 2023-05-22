<?php

namespace App\Trait;

use Doctrine\ORM\Mapping as ORM;

trait Active
{
    #[ORM\Column]
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