<?php

namespace App\Entity;

use App\Repository\WeeksheetRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=WeeksheetRepository::class)
 * @ORM\Table(name="weeksheet")
 */
class Weeksheet
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="weeksheets")
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     */
    private $user;

    /**
     * @ORM\Column(type="datetime")
     */
    private $beginAt;

    /**
     * @ORM\Column(type="datetime")
     */
    private $finishAt;

    /**
     * @ORM\OneToMany(targetEntity=WorkDay::class, mappedBy="weeksheet", orphanRemoval=true)
     */
    private $workDays;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isUpdated;

    /**
     * @ORM\Column(type="array", nullable=true)
     */
    private $updateDescription = [];

    private $duration;

    /**
     * @ORM\ManyToOne(targetEntity=SheetState::class, inversedBy="weeksheets")
     */
    private $state;

    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     */
    private $validator;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $comment;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $signed_at;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $adminComment;

    public function setDuration(): self{
        $duration = 0;
        foreach ($this->workDays as $key => $day) {
            $duration+=($day->getDuration());
        }
        $this->duration = $duration;

        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getBeginAt(): ?\DateTimeInterface
    {
        return $this->beginAt;
    }

    public function setBeginAt(\DateTimeInterface $beginAt): self
    {
        $this->beginAt = $beginAt;

        return $this;
    }

    /**
     * @return Collection|WorkDay[]
     */
    public function getWorkDays(): Collection
    {
        return $this->workDays;
    }

    public function addWorkDay(WorkDay $WorkDay): self
    {
        if (!$this->workDays->contains($WorkDay)) {
            $this->workDays[] = $WorkDay;
            $WorkDay->setWeeksheet($this);
        }

        return $this;
    }

    public function removeWorkDay(WorkDay $WorkDay): self
    {
        if ($this->workDays->contains($WorkDay)) {
            $this->workDays->removeElement($WorkDay);
            // set the owning side to null (unless already changed)
            if ($WorkDay->getWeeksheet() === $this) {
                $WorkDay->setWeeksheet(null);
            }
        }

        return $this;
    }


    /**
     * Get the value of isUpdated
     */ 
    public function getIsUpdated(): ?bool
    {
        return $this->isUpdated;
    }

    /**
     * Set the value of isUpdated
     *
     * @return  self
     */ 
    public function setIsUpdated(bool $isUpdated)
    {
        $this->isUpdated = $isUpdated;

        return $this;
    }

    /**
     * Get the value of finishAt
     */ 
    public function getFinishAt(): ?\DateTimeInterface
    {
        return $this->finishAt;
    }

    /**
     * Set the value of finishAt
     *
     * @return  self
     */ 
    public function setFinishAt(\DateTimeInterface $finishAt)
    {
        $this->finishAt = $finishAt;

        return $this;
    }

    public function getUpdateDescription(): ?array
    {
        return $this->updateDescription;
    }

    public function setUpdateDescription(?string $updateDescription): self
    {
        $this->updateDescription[] = $updateDescription;

        return $this;
    }

    /**
     * Get the value of duration
     */ 
    public function getDuration(): int
    {
        if (!$this->duration) {
            $this->setDuration();
        }
        return $this->duration;
    }

    public function getState(): ?SheetState
    {
        return $this->state;
    }

    public function setState(?SheetState $state): self
    {
        $this->state = $state;

        return $this;
    }

    public function getValidator(): ?User
    {
        return $this->validator;
    }

    public function setValidator(?User $validator): self
    {
        $this->validator = $validator;

        return $this;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(?string $comment): self
    {
        $this->comment = $comment;

        return $this;
    }

    public function getSignedAt(): ?\DateTimeInterface
    {
        return $this->signed_at;
    }

    public function setSignedAt(?\DateTimeInterface $signed_at): self
    {
        $this->signed_at = $signed_at;

        return $this;
    }

    public function getAdminComment(): ?string
    {
        return $this->adminComment;
    }

    public function setAdminComment(?string $adminComment): self
    {
        $this->adminComment = $adminComment;

        return $this;
    }
}
