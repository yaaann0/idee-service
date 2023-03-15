<?php

namespace App\Entity;

use App\Repository\TaskRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=TaskRepository::class)
 * @ORM\Table(name="task")
 */
class Task
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=user::class, inversedBy="tasks")
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     */
    /* private $user; */

    /**
     * @ORM\Column(type="string", length=100)
     * @Assert\Length(max=100)
     */
    private $clientName;

    /**
     * @ORM\Column(type="time")
     * @Assert\NotBlank()
     */
    private $beginAt;

    /**
     * @ORM\Column(type="time")
     * @Assert\NotBlank()
     * @Assert\GreaterThan(
     *      propertyPath="beginAt",
     *      message="L'heure de fin doit être après celle du début"
     *  )
     */
    private $endAt;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isUpdated;

    /**
     * @ORM\ManyToOne(targetEntity=WorkDay::class, inversedBy="tasks")
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     */
    private $workDay;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $updateDescription;

    public function getId(): ?int
    {
        return $this->id;
    }

/*     public function getUser(): ?user
    {
        return $this->user;
    }

    public function setUser(?user $user): self
    {
        $this->user = $user;

        return $this;
    } */

    public function getClientName(): ?string
    {
        return $this->clientName;
    }

    public function setClientName(string $clientName): self
    {
        $this->clientName = $clientName;

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

    public function getEndAt(): ?\DateTimeInterface
    {
        return $this->endAt;
    }

    public function setEndAt(\DateTimeInterface $endAt): self
    {
        $this->endAt = $endAt;

        return $this;
    }

    public function getDuration(): \DateInterval{
        return $this->endAt->diff($this->beginAt, true);
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

    public function getWorkDay(): ?WorkDay
    {
        return $this->workDay;
    }

    public function setWorkDay(?WorkDay $workDay): self
    {
        $this->workDay = $workDay;

        return $this;
    }

    public function getUpdateDescription(): ?string
    {
        return $this->updateDescription;
    }

    public function setUpdateDescription(?string $updateDescription): self
    {
        if (!$updateDescription) {
            $this->updateDescription = $updateDescription;
            return $this;
        }

        $this->updateDescription = substr($updateDescription . $this->updateDescription, 0, 250);

        return $this;
    }
}
