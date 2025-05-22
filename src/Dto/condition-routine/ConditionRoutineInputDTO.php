<?php

namespace App\Dto\ConditionRoutine;

class ConditionRoutineInputDTO
{
    public ?string $name = null;
    public ?string $description = null;
    public ?string $taskTime = null; // Format HH:MM:SS
    public ?int $categoryId = null;
    /** @var array<int>|null */
    public ?array $days = null;
    public ?string $responseCondition = null;
    public ?int $questionId = null;
}
