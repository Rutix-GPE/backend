<?php

namespace App\Dto\Routine;

use App\Entity\Routine;

class RoutineOutputDTO
{
    public int $id;
    public ?int $categoryId = null;
    public ?int $userId = null;
    public string $name;
    public string $description;
    public string $taskTime;
    public string $creationDate;
    public string $updatedDate;
    /** @var array<int> */
    public array $days;

    public function __construct(Routine $routine)
    {
        $this->id = $routine->getId();
        $this->categoryId = $routine->getCategory()?->getId();
        $this->userId = $routine->getUser()?->getId();
        $this->name = $routine->getName();
        $this->description = $routine->getDescription();
        $this->taskTime = $routine->getTaskTime()?->format('H:i:s');
        $this->creationDate = $routine->getCreationDate()?->format('Y-m-d H:i:s');
        $this->updatedDate = $routine->getUpdatedDate()?->format('Y-m-d H:i:s');
        $this->days = $routine->getDays(); // tableau JSON
    }
}
