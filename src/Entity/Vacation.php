<?php

namespace App\Entity;

/**
 */
class Vacation
{
    /**
     * @var \DateTimeInterface|null
     */
    private $beginAt;

    /**
     * @var \DateTimeInterface|null
     */
    private $finishAt;

    public function getBeginAt(): ?\DateTimeInterface
    {
        return $this->beginAt;
    }

    public function setBeginAt(?\DateTimeInterface $beginAt): self
    {
        $this->beginAt = $beginAt;

        return $this;
    }

    public function getFinishAt(): ?\DateTimeInterface
    {
        return $this->finishAt;
    }

    public function setFinishAt(?\DateTimeInterface $finishAt): self
    {
        $this->finishAt = $finishAt;

        return $this;
    }
}
