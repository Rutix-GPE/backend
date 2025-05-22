<?php
namespace App\Dto\Task;

use App\Dto\Category\CategoryDTO;
use App\Dto\User\UserResponseDTO;
use Symfony\Component\Validator\Constraints as Assert;

class TaskInputDto
{
    #[Assert\NotBlank()]
    public string $name;

    #[Assert\NotBlank()]
    public string $description;

    #[Assert\NotBlank()]
    #[Assert\Date()]
    public string $taskDate;

    #[Assert\NotBlank()]
    #[Assert\Time()]
    public string $taskTime;
    #[Assert\NotBlank()]
    public int $user;
    public ?CategoryDTO $category = null;
}
