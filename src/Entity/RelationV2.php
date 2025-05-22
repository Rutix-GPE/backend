<?php

namespace App\Entity;

use App\Repository\RelationV2Repository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RelationV2Repository::class)]
class RelationV2
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'relationV2s')]
    private ?QuestionV2 $source = null;

    #[ORM\ManyToOne(inversedBy: 'allTargetQuestion')]
    private ?QuestionV2 $targetQuestion = null;

    #[ORM\ManyToOne(inversedBy: 'relationV2s')]
    private ?RoutineV2 $targetRoutine = null;

    #[ORM\Column(length: 50)]
    private ?string $answer = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSource(): ?QuestionV2
    {
        return $this->source;
    }

    public function setSource(?QuestionV2 $source): static
    {
        $this->source = $source;

        return $this;
    }

    public function getTargetQuestion(): ?QuestionV2
    {
        return $this->targetQuestion;
    }

    public function setTargetQuestion(?QuestionV2 $targetQuestion): static
    {
        $this->targetQuestion = $targetQuestion;

        return $this;
    }

    public function getTargetRoutine(): ?RoutineV2
    {
        return $this->targetRoutine;
    }

    public function setTargetRoutine(?RoutineV2 $targetRoutine): static
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
}
