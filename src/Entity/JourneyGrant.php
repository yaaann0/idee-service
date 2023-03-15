<?php

namespace App\Entity;

/**
 */
class JourneyGrant
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
     * @var Journeysheet
     */
    private $journeySheet;

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

    public function getJourneySheet(): ?JourneySheet
    {
        return $this->journeySheet;
    }

    public function setJourneySheet(?JourneySheet $journeySheet): self
    {
        $this->journeySheet = $journeySheet;

        return $this;
    }
}
