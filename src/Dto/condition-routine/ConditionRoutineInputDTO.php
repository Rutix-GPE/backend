<?php
namespace App\Dto\ConditionRoutine;

use Symfony\Component\Validator\Constraints as Assert;

class ConditionRoutineInputDTO
{
    #[Assert\NotBlank]
    private ?string $name = null;

    #[Assert\NotBlank]
    private ?string $description = null;

    #[Assert\NotBlank]
    #[Assert\Time]
    private ?string $taskTime = null;

    #[Assert\NotBlank]
    private array $days = [];

    #[Assert\NotBlank]
    private ?string $responseCondition = null;

    #[Assert\Type("int")]
    private ?int $categoryId = null;

    #[Assert\Type("int")]
    private ?int $questionId = null;

    // GETTERS
    public function getName(): ?string { return $this->name; }
    public function getDescription(): ?string { return $this->description; }
    public function getTaskTime(): ?string { return $this->taskTime; }
    public function getDays(): array { return $this->days; }
    public function getResponseCondition(): ?string { return $this->responseCondition; }
    public function getCategoryId(): ?int { return $this->categoryId; }
    public function getQuestionId(): ?int { return $this->questionId; }

    // SETTERS
    public function setName(?string $name): void { $this->name = $name; }
    public function setDescription(?string $description): void { $this->description = $description; }
    public function setTaskTime(?string $taskTime): void { $this->taskTime = $taskTime; }
    public function setDays(array $days): void { $this->days = $days; }
    public function setResponseCondition(?string $responseCondition): void { $this->responseCondition = $responseCondition; }
    public function setCategoryId(?int $categoryId): void { $this->categoryId = $categoryId; }
    public function setQuestionId(?int $questionId): void { $this->questionId = $questionId; }
}
