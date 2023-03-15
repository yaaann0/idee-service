<?php

namespace App\Entity;

class VacationChoices
{
        /**
     * @var string
     */
    private $reason;

    private $first;

    private $second;

    public function getReason(): ?string
    {
        return $this->reason;
    }

    public function setReason(string $reason): self
    {
        $this->reason = $reason;

        return $this;
    }

    public function getFirst(): ?Vacation
    {
        return $this->first;
    }

    public function setFirst(Vacation $first): self
    {
        $this->first = $first;

        return $this;
    }

    public function getSecond(): ?Vacation
    {
        return $this->second;
    }

    public function setSecond(?Vacation $second): self
    {
        $this->second = $second;

        return $this;
    }
}
