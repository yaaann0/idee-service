<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;

/**
 */
class JourneySheet
{

    /**
     * @var string|null
     * @Assert\NotBlank()
     */
    private $month;

    /**
     * @var integer|null
     * @Assert\NotBlank()
     */
    private $year;

    /**
     */
    private $journeygrants;

    public function __construct()
    {
        $this->journeygrants = new ArrayCollection();
    }

    /**
     * @return Collection|JourneyGrant[]
     */
    public function getJourneygrants(): Collection
    {
        return $this->journeygrants;
    }

    public function addJourneygrant(JourneyGrant $journeyGrant): self
    {
        if (!$this->journeygrants->contains($journeyGrant)) {
            $this->journeygrants[] = $journeyGrant;
            $journeyGrant->setJourneySheet($this);
        }

        return $this;
    }

    public function removeJourneygrant(JourneyGrant $journeyGrant): self
    {
        if ($this->journeygrants->removeElement($journeyGrant)) {
            // set the owning side to null (unless already changed)
            if ($journeyGrant->getJourneySheet() === $this) {
                $journeyGrant->setJourneySheet(null);
            }
        }

        return $this;
    }

    /**
     * Get the value of month
     *
     * @return  string|null
     */ 
    public function getMonth()
    {
        return $this->month;
    }

    /**
     * Set the value of month
     *
     * @param  string|null  $month
     *
     * @return  self
     */ 
    public function setMonth($month)
    {
        $this->month = $month;

        return $this;
    }

    /**
     * Get value=2020,
     *
     * @return  integer|null
     */ 
    public function getYear()
    {
        return $this->year;
    }

    /**
     * Set value=2020,
     *
     * @param  integer|null  $year  value=2020,
     *
     * @return  self
     */ 
    public function setYear($year)
    {
        $this->year = $year;

        return $this;
    }
}
