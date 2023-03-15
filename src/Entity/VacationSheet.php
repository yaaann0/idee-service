<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 */
class VacationSheet
{

    /**
     */
    private $vacations;

    /**
     */
    private $user;

    public function __construct()
    {
        $this->vacations = new ArrayCollection();
    }

    /**
     * @return Collection|VacationChoices[]
     */
    public function getVacations(): Collection
    {
        return $this->vacations;
    }

    public function addVacation(VacationChoices $vacation): self
    {
        if (!$this->vacations->contains($vacation)) {
            $this->vacations[] = $vacation;
        }

        return $this;
    }

    public function removeVacation(VacationChoices $vacation): self
    {
        $this->vacations->removeElement($vacation);
        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }
}
