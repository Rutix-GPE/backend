<?php
namespace App\Dto\Task;

use App\Entity\Task;

class TaskOutputDTO
{
    public int $id;
    public string $name;
    public string $description;
    public string $taskDate;
    public string $taskTime;
    public string $status;
    public string $creationDate;
    public string $updatedDate;
    public int $user;
    public ?int $category;

    public function __construct(Task $task)
    {
        $this->id = $task->getId();
        $this->name = $task->getName();
        $this->description = $task->getDescription();

        // Utilisation du format ISO 8601
        $this->taskDate = $task->getTaskDate()?->format(DATE_ATOM); // ou 'c'
        $this->taskTime = $task->getTaskTime()?->format(DATE_ATOM); // idem

        $this->status = $task->getStatus();
        $this->creationDate = $task->getCreationDate()?->format(DATE_ATOM);
        $this->updatedDate = $task->getUpdatedDate()?->format(DATE_ATOM);

        $this->user = $task->getUser()->getId();
        $this->category = $task->getCategory()?->getId();
    }
}
