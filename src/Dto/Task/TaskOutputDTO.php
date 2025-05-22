<?php
namespace App\Dto;

use App\Dto\Category\CategoryDTO;
use App\Dto\User\UserResponseDTO;

class TaskOutputDto
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
}
