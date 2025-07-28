<?php

namespace App\Entity;

use App\Repository\RelationRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types;

#[ORM\Entity(repositoryClass: RelationRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Relation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'relations')]
    private ?Question $source = null;

    #[ORM\ManyToOne(inversedBy: 'allTargetQuestion')]
    private ?Question $targetQuestion = null;

    #[ORM\ManyToOne(inversedBy: 'relations')]
    private ?Routine $targetRoutine = null;

    #[ORM\Column(length: 50)]
    private ?string $answer = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    public ?\DateTimeInterface $CreationDate = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    public ?\DateTimeInterface $UpdatedDate = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSource(): ?Question
    {
        return $this->source;
    }

    public function setSource(?Question $source): static
    {
        $this->source = $source;

        return $this;
    }

    public function getTargetQuestion(): ?Question
    {
        return $this->targetQuestion;
    }

    public function setTargetQuestion(?Question $targetQuestion): static
    {
        $this->targetQuestion = $targetQuestion;

        return $this;
    }

    public function getTargetRoutine(): ?Routine
    {
        return $this->targetRoutine;
    }

    public function setTargetRoutine(?Routine $targetRoutine): static
    {
        $this->targetRoutine = $targetRoutine;

        return $this;
    }

    public function getAnswer(): ?string
    {
        return $this->answer;
    }

    public function setAnswer(string $answer): static
    {
        $this->answer = $answer;

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
