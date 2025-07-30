<?php

namespace App\Entity;

use App\Repository\QuestionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types;

#[ORM\Entity(repositoryClass: QuestionRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Question
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50, unique: true)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $content = null;

    #[ORM\Column]
    private ?bool $isRootQuestion = null;

    #[ORM\Column]
    private ?bool $isQuickQuestion = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    public ?\DateTimeInterface $CreationDate = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    public ?\DateTimeInterface $UpdatedDate = null;

    // public function __construct()
    // {
    //     $this->allSources = new ArrayCollection();
    //     $this->allTargetQuestions = new ArrayCollection();
    // }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): static
    {
        $this->content = $content;

        return $this;
    }

    public function isRootQuestion(): ?bool
    {
        return $this->isRootQuestion;
    }

    public function setIsRootQuestion(bool $isRootQuestion): static
    {
        $this->isRootQuestion = $isRootQuestion;

        return $this;
    }

    public function isQuickQuestion(): ?bool
    {
        return $this->isQuickQuestion;
    }

    public function setIsQuickQuestion(bool $isQuickQuestion): static
    {
        $this->isQuickQuestion = $isQuickQuestion;

        return $this;
    }

    public function getCreationDate(): ?\DateTimeInterface
    {
        return $this->CreationDate;
    }

    public function setCreationDate(?\DateTimeInterface $CreationDate): static
    {
        $this->CreationDate = $CreationDate;

        return $this;
    }

    public function getUpdatedDate(): ?\DateTimeInterface
    {
        return $this->UpdatedDate;
    }

    public function setUpdatedDate(?\DateTimeInterface $UpdatedDate): static
    {
        $this->UpdatedDate = $UpdatedDate;

        return $this;
    }

    #[ORM\PrePersist]
    public function prePersist()
    {
        $this->CreationDate = new \DateTime();
        $this->UpdatedDate = new \DateTime();
        
        return $this;
    }

    #[ORM\PreUpdate]
    public function preUpdate()
    {
        $this->UpdatedDate = new \DateTime();

        return $this;
    }

}
