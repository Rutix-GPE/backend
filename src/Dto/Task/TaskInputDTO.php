<?php
namespace App\Dto\Task;

use Symfony\Component\Validator\Constraints as Assert;

class TaskInputDTO
{
    #[Assert\NotBlank()]
    public string $name;

    public string $description;

    #[Assert\NotBlank()]
    #[Assert\Type(\DateTimeInterface::class)]
    public \DateTimeInterface $taskDate;

    #[Assert\NotBlank()]
    #[Assert\Type(\DateTimeInterface::class)]
    public \DateTimeInterface $taskTime;

    public ?string $status = null;

}
