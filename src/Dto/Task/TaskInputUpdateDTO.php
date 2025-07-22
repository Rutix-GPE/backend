<?php
namespace App\Dto\Task;
class TaskInputUpdateDTO
{
    public ?string $name = null;
    public ?string $description = null;
    public ?string $taskDate = null;
    public ?string $taskTime = null;
    public ?string $status = null;
}