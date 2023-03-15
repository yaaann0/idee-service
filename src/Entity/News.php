<?php

namespace App\Entity;

use App\Repository\NewsRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass=NewsRepository::class)
 * @ORM\Table(name="news")
 * @Vich\Uploadable()
 */
class News
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(max=255)
     * @Assert\NotBlank()
     */
    private $title;

    /**
     * @var File|null
     * @Vich\UploadableField(mapping="news_file", fileNameProperty="newsFileName")
     * 
     */
    private $newsFile;

    /**
     * @var string|null
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $newsFileName;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updatedAt;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get the value of newsFile
     *
     * @return  File|null
     */ 
    public function getNewsFile(): ?File
    {
        return $this->newsFile;
    }

    /**
     * Set the value of newsFile
     *
     * @param  File|null  $newsFile
     *
     * @return  self
     */ 
    public function setNewsFile(?File $newsFile)
    {
        $this->newsFile = $newsFile;

        if ($this->newsFile instanceof UploadedFile) {
            $this->updatedAt = new \DateTime();
        }

        return $this;
    }

    /**
     * Get the value of newsFileName
     *
     * @return  string|null
     */ 
    public function getNewsFileName(): ?string
    {
        return $this->newsFileName;
    }

    /**
     * Set the value of newsFileName
     *
     * @param  string|null  $newsFileName
     *
     * @return  self
     */ 
    public function setNewsFileName(?string $newsFileName)
    {
        $this->newsFileName = $newsFileName;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }
}
