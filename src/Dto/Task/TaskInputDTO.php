<?php
namespace App\Dto\Task;
use Symfony\Component\Validator\Constraints as Assert;

class TaskInputDTO
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
}
