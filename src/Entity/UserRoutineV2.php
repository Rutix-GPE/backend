<?php

namespace App\Entity;

use App\Repository\UserRoutineV2Repository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserRoutineV2Repository::class)]
class UserRoutineV2
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $name = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(type: Types::ARRAY)]
    private array $days = [];

    #[ORM\Column(type: Types::TIME_MUTABLE)]
    private ?\DateTime $taskTime = null;

    #[ORM\Column]
    private ?bool $isAllTaskGenerated = null;

    #[ORM\ManyToOne(inversedBy: 'userRoutineV2s')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getDays(): array
    {
        return $this->days;
    }

    public function setDays(array $days): static
    {
        $this->days = $days;

        return $this;
    }

    public function getTaskTime(): ?\DateTime
    {
        return $this->taskTime;
    }

    public function setTaskTime(\DateTime $taskTime): static
    {
        $this->taskTime = $taskTime;

        return $this;
    }

    public function isAllTaskGenerated(): ?bool
    {
        return $this->isAllTaskGenerated;
    }

    public function setIsAllTaskGenerated(bool $isAllTaskGenerated): static
    {
        $this->isAllTaskGenerated = $isAllTaskGenerated;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }
}
