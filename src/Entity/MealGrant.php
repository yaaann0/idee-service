<?php

namespace App\Entity;

/**
 */
class MealGrant
{

    /**
     * @var \DateTimeInterface|null
     */
    private $createdAt;

    /**
     * @var string
     */
    private $manager;

    /**
     * @var string
     */
    private $client;

    /**
     * @var string
     */
    private $city;

    /**
     * @var integer
     */
    private $distance;

    /**
     * @var Sector
     */
    private $sector;

    /**
     * @var integer
     */
    private $mealNumber;

    /**
     * @var Mealsheet
     */
    private $mealSheet;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getManager(): ?string
    {
        return $this->manager;
    }

    public function setManager(string $manager): self
    {
        $this->manager = $manager;

        return $this;
    }

    public function getClient(): ?string
    {
        return $this->client;
    }

    public function setClient(string $client): self
    {
        $this->client = $client;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getDistance(): ?int
    {
        return $this->distance;
    }

    public function setDistance(int $distance): self
    {
        $this->distance = $distance;

        return $this;
    }

    public function getSector(): ?Sector
    {
        return $this->sector;
    }

    public function setSector(?Sector $sector): self
    {
        $this->sector = $sector;

        return $this;
    }

    public function getMealNumber(): ?int
    {
        return $this->mealNumber;
    }

    public function setMealNumber(int $mealNumber): self
    {
        $this->mealNumber = $mealNumber;

        return $this;
    }

    public function getMealSheet(): ?MealSheet
    {
        return $this->mealSheet;
    }

    public function setMealSheet(?MealSheet $mealSheet): self
    {
        $this->mealSheet = $mealSheet;

        return $this;
    }
}
