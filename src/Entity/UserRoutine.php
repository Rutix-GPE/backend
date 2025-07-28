<?php

namespace App\Entity;

use App\Repository\UserRoutineRepository;
use Doctrine\DBAL\Types\Types;
use Symfony\Component\Serializer\Annotation\Groups;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserRoutineRepository::class)]
#[ORM\HasLifecycleCallbacks]
class UserRoutine
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['routine:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    #[Groups(['routine:read', 'routine:write'])]
    private ?string $name = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['routine:read', 'routine:write'])]
    private ?string $description = null;

    #[ORM\Column]
    #[Groups(['routine:read', 'routine:write'])]
    private array $days = [];

    #[ORM\Column(type: Types::TIME_MUTABLE)]
    #[Groups(['routine:read', 'routine:write'])]
    private ?\DateTime $taskTime = null;

    #[ORM\Column]
    #[Groups(['routine:read', 'routine:write'])]
    private ?bool $isAllTaskGenerated = null;

    #[ORM\ManyToOne(inversedBy: 'userRoutines')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['routine:write'])]
    private ?User $user = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Groups(['routine:read'])]
    private ?\DateTimeInterface $creationDate = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Groups(['routine:read'])]
    private ?\DateTimeInterface $updatedDate = null;

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

    public function getCreationDate(): ?\DateTimeInterface
    {
        return $this->creationDate;
    }

    public function setCreationDate(\DateTimeInterface $creationDate): static
    {
        $this->creationDate = $creationDate;

        return $this;
    }

    public function getUpdatedDate(): ?\DateTimeInterface
    {
        return $this->updatedDate;
    }

    public function setUpdatedDate(\DateTimeInterface $updatedDate): static
    {
        $this->updatedDate = $updatedDate;

        return $this;
    }

    #[ORM\PrePersist]
    public function prePersist()
    {
        $this->creationDate = new \DateTime();
        $this->updatedDate = new \DateTime();
        
        return $this;
    }

    #[ORM\PreUpdate]
    public function preUpdate()
    {
        $this->updatedDate = new \DateTime();

        return $this;
    }

    public function copyRoutine(Routine $routine, $user)
    {
        $this->user = $user;
        $this->name = $routine->getName();
        $this->description = $routine->getDescription();
        $this->days = $routine->getDays();
        $this->taskTime = $routine->getTaskTime();
        $this->isAllTaskGenerated = false;
    }
}
