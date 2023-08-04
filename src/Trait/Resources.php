<?php

namespace App\Trait;

use App\Entity\Resource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

trait Resources
{

    #[ORM\OneToMany(mappedBy: 'target', targetEntity: Resource::class)]
    #[Groups(['Challenge', 'Challenge:POST', 'Challenges'])]
    private Collection $resources;

    public function __construct()
    {
        $this->resources = new ArrayCollection();
    }

    /**
     * @return Collection<int, Resource>
     */
    public function getResources(): Collection
    {
        //dump('ici');
        return $this->resources;
    }

    public function addResource(Resource $resource): self
    {
        if (!$this->resources->contains($resource)) {
            $this->resources->add($resource);
            $resource->setTarget($this);
        }

        return $this;
    }

    public function removeResource(Resource $resource): self
    {
        if ($this->resources->removeElement($resource)) {
            // set the owning side to null (unless already changed)
            if ($resource->getTarget() === $this) {
                $resource->setTarget(null);
            }
        }

        return $this;
    }
}