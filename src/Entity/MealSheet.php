<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;

/**
 */
class MealSheet
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
    private $mealgrants;

    public function __construct()
    {
        $this->mealgrants = new ArrayCollection();
    }

    /**
     * @return Collection|MealGrant[]
     */
    public function getMealgrants(): Collection
    {
        return $this->mealgrants;
    }

    public function addMealgrant(MealGrant $mealGrant): self
    {
        if (!$this->mealgrants->contains($mealGrant)) {
            $this->mealgrants[] = $mealGrant;
            $mealGrant->setMealSheet($this);
        }

        return $this;
    }

    public function removeMealgrant(MealGrant $mealGrant): self
    {
        if ($this->mealgrants->removeElement($mealGrant)) {
            // set the owning side to null (unless already changed)
            if ($mealGrant->getMealSheet() === $this) {
                $mealGrant->setMealSheet(null);
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
