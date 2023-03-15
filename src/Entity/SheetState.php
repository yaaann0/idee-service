<?php

namespace App\Entity;

use App\Repository\SheetStateRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SheetStateRepository::class)
 */
class SheetState
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $slug;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $label;

    /**
     * @ORM\OneToMany(targetEntity=Weeksheet::class, mappedBy="state")
     */
    private $weeksheets;

    public function __construct()
    {
        $this->weeksheets = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(string $label): self
    {
        $this->label = $label;

        return $this;
    }

    /**
     * @return Collection|Weeksheet[]
     */
    public function getWeeksheets(): Collection
    {
        return $this->weeksheets;
    }

    public function addWeeksheet(Weeksheet $weeksheet): self
    {
        if (!$this->weeksheets->contains($weeksheet)) {
            $this->weeksheets[] = $weeksheet;
            $weeksheet->setState($this);
        }

        return $this;
    }

    public function removeWeeksheet(Weeksheet $weeksheet): self
    {
        if ($this->weeksheets->removeElement($weeksheet)) {
            // set the owning side to null (unless already changed)
            if ($weeksheet->getState() === $this) {
                $weeksheet->setState(null);
            }
        }

        return $this;
    }
}
