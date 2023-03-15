<?php

namespace App\Entity;

use App\Repository\WorkDayRepository;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=WorkDayRepository::class)
 * @ORM\Table(name="workday")
 */
class WorkDay
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToMany(
     *      targetEntity=Task::class,
     *      mappedBy="workDay",
     *      fetch="EXTRA_LAZY",
     *      orphanRemoval=true,
     *      cascade={"persist"}
     * )
     */
    private $tasks;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="workDays")
     */
    private $user;

    /**
     * @ORM\Column(type="datetime")
     */
    private $datetime;

    /**
     * @ORM\ManyToOne(targetEntity=Weeksheet::class, inversedBy="workDays")
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     */
    private $weeksheet;

    public function __construct()
    {
        $this->tasks = new ArrayCollection();
    }

    public function getDuration(): int{
        $duration = new DateTime();
        $duration->setTimestamp(0);
        foreach ($this->tasks as $key => $task) {
            $duration->add($task->getDuration());
        }
        return $duration->getTimestamp();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection|Task[]
     */
    public function getTasks(): Collection
    {
        return $this->tasks;
    }

    public function addTask(Task $task): self
    {
        if (!$this->tasks->contains($task)) {
            $this->tasks[] = $task;
            $task->setWorkDay($this);
        }

        return $this;
    }

    public function removeTask(Task $task): self
    {
        if ($this->tasks->contains($task)) {
            $this->tasks->removeElement($task);
            // set the owning side to null (unless already changed)
            if ($task->getWorkDay() === $this) {
                $task->setWorkDay(null);
            }
        }

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

    public function getDatetime(): ?\DateTimeInterface
    {
        return $this->datetime;
    }

    public function setDatetime(\DateTimeInterface $datetime): self
    {
        $this->datetime = $datetime;

        return $this;
    }

    public function getWeeksheet(): ?Weeksheet
    {
        return $this->weeksheet;
    }

    public function setWeeksheet(?Weeksheet $weeksheet): self
    {
        $this->weeksheet = $weeksheet;

        return $this;
    }
}
