<?php

namespace App\Entity;

use App\Repository\RoutineV2Repository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RoutineV2Repository::class)]
class RoutineV2
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    public function getId(): ?int
    {
        return $this->id;
    }
}
