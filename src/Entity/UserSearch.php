<?php

namespace App\Entity;

class UserSearch extends DefaultSearch
{
    /**
     * @var string|null
     */
    private $lastname;

    /**
     * @var string|null
     */
    private $firstname;

    /**
     * @var Department|null
     */
    private $department;

    /**
     * @var bool|null
     */
    private $isActive;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(?string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(?string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getDepartment(): ?Department
    {
        return $this->department;
    }

    public function setDepartment(?Department $department): self
    {
        $this->department = $department;

        return $this;
    }

    /**
     * Get the value of isActive
     *
     * @return  bool|null
     */ 
    public function getIsActive(): ?bool
    {
        return $this->isActive;
    }

    /**
     * Set the value of isActive
     *
     * @param  bool|null  $isActive
     *
     * @return  self
     */ 
    public function setIsActive(?bool $isActive)
    {
        $this->isActive = $isActive;

        return $this;
    }
}
