<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\ConditionRoutineRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ConditionRoutineRepository::class)]
class ConditionRoutine
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $description = null;

    #[ORM\Column(type: Types::TIME_MUTABLE)]
    private ?\DateTimeInterface $taskTime = null;

    #[ORM\ManyToOne(inversedBy: 'conditionRoutines')]
    private ?Category $category = null;

    #[ORM\Column(type: Types::JSON)]
    private array $days = [1,2,3,4,5];



    #[ORM\Column(length: 255)]
    private ?string $responseCondition = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $creationDate = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $updatedDate = null;

    #[ORM\ManyToOne(inversedBy: 'conditionRoutines')]
    private ?TemplateQuestion $Question = null;

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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getTaskTime(): ?\DateTimeInterface
    {
        return $this->taskTime;
    }

    public function setTaskTime(\DateTimeInterface $taskTime): static
    {
        $this->taskTime = $taskTime;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): static
    {
        $this->category = $category;

        return $this;
    }

    public function getDays(): array
    {
        return $this->days;
    }

    public function setDays(array $days): static
    {
        $this->days = $days;

        return $this;
    }

    public function getResponseCondition(): ?string
    {
        return $this->responseCondition;
    }

    public function setResponseCondition(string $responseCondition): static
    {
        $this->responseCondition = $responseCondition;

        return $this;
    }

    public function getCreationDate(): ?\DateTimeInterface
    {
        return $this->creationDate;
    }

    public function setCreationDate(?\DateTimeInterface $creationDate): static
    {
        $this->creationDate = $creationDate;

        return $this;
    }

    public function getUpdatedDate(): ?\DateTimeInterface
    {
        return $this->updatedDate;
    }

    public function setUpdatedDate(?\DateTimeInterface $updatedDate): static
    {
        $this->updatedDate = $updatedDate;

        return $this;
    }

    public function getQuestion(): ?TemplateQuestion
    {
        return $this->Question;
    }

    public function setQuestion(?TemplateQuestion $Question): static
    {
        $this->Question = $Question;

        return $this;
    }
}
