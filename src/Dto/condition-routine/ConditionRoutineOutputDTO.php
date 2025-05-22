<?php

namespace App\Dto\ConditionRoutine;

use App\Entity\ConditionRoutine;

class ConditionRoutineOutputDTO
{
    public int $id;
    public string $name;
    public string $description;
    public string $taskTime;
    public ?int $categoryId;
    /** @var array<int> */
    public array $days;
    public string $responseCondition;
    public ?string $creationDate;
    public ?string $updatedDate;
    public ?int $questionId;

    public function __construct(ConditionRoutine $entity)
    {
        $this->id = $entity->getId();
        $this->name = $entity->getName();
        $this->description = $entity->getDescription();
        $this->taskTime = $entity->getTaskTime()?->format('H:i:s');
        $this->categoryId = $entity->getCategory()?->getId();
        $this->days = $entity->getDays();
        $this->responseCondition = $entity->getResponseCondition();
        $this->creationDate = $entity->getCreationDate()?->format('Y-m-d H:i:s');
        $this->updatedDate = $entity->getUpdatedDate()?->format('Y-m-d H:i:s');
        $this->questionId = $entity->getQuestion()?->getId();
    }
}
