<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\UserRoutineRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserRoutineRepository::class)]
#[ApiResource]
class UserRoutine
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $response = null;

    #[ORM\OneToOne(inversedBy: 'userRoutine', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?UserResponse $UserResponse = null;

    #[ORM\ManyToOne(inversedBy: 'userRoutines')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Routine $Routine = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getResponse(): ?string
    {
        return $this->response;
    }

    public function setResponse(string $response): static
    {
        $this->response = $response;

        return $this;
    }

    public function getUserResponse(): ?UserResponse
    {
        return $this->UserResponse;
    }

    public function setUserResponse(UserResponse $UserResponse): static
    {
        $this->UserResponse = $UserResponse;

        return $this;
    }

    public function getRoutine(): ?Routine
    {
        return $this->Routine;
    }

    public function setRoutine(?Routine $Routine): static
    {
        $this->Routine = $Routine;

        return $this;
    }
}
