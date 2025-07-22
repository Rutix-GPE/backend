<?php

namespace App\Dto\ConditionRoutine;

use App\Entity\ConditionRoutine;

class ConditionRoutineOutputDTO
{
    public int $id;
    public string $name;
    public string $description;
    public string $time;
    public string $response;
    public int $question;
    public ?int $category;
    
    public function __construct(ConditionRoutine $conditionRoutine)
    {
        $this->id = $conditionRoutine->getId();
        $this->name = $conditionRoutine->getName();
        $this->description = $conditionRoutine->getDescription();
        $this->time = $conditionRoutine->getTaskTime()?->format(DATE_ATOM);
        $this->response = $conditionRoutine->getResponseCondition();
        $this->question = $conditionRoutine->getQuestion()?->getId();
        $this->category = $conditionRoutine->getCategory()?->getId();
    }
}
