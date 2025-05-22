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
        $this->taskDate = $task->getTaskDate()->format('Y-m-d');
        $this->taskTime = $task->getTaskTime()->format('H:i:s');
        $this->status = $task->getStatus();
        $this->creationDate = $task->getCreationDate()->format('Y-m-d');
        $this->updatedDate = $task->getUpdatedDate()->format('Y-m-d');
        $this->user = $task->getUser()->getId();
        $category = $task->getCategory()?->getId();
    }
}
