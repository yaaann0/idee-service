<?php

namespace App\Entity;

class WeeksheetSearch extends DefaultSearch
{
    /**
     * @var string|null
     */
    private $lastname;

    /**
     * @var \Datetime|null
     */
    private $from;

    /**
     * @var \Datetime|null
     */
    private $to;

    /**
     * @var string|null
     */
    private $state;


    /**
     * @var bool|null
     */
    private $isUpdated;

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getFrom(): ?\DateTimeInterface
    {
        return $this->from;
    }

    public function setFrom(?\DateTimeInterface $from): self
    {
        $this->from = $from;

        return $this;
    }

    public function getTo(): ?\DateTimeInterface
    {
        return $this->to;
    }

    public function setTo(?\DateTimeInterface $to): self
    {
        $this->to = $to;

        return $this;
    }

    public function getIsUpdated(): ?bool
    {
        return $this->isUpdated;
    }

    public function setIsUpdated(bool $isUpdated): self
    {
        $this->isUpdated = $isUpdated;

        return $this;
    }

    public function getState(): ?string
    {
        return $this->state;
    }

    public function setState(?string $state): self
    {
        $this->state = $state;

        return $this;
    }
}
