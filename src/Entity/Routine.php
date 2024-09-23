<?php

namespace App\Entity;

use App\Repository\RoutineRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: RoutineRepository::class)]
class Routine
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['category:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\Column(type: Types::TIME_MUTABLE)]
    private ?\DateTimeInterface $routineTime = null;

    #[ORM\Column(length: 50)]
    private ?string $status = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $creationDate = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $updatedDate = null;

    #[ORM\ManyToOne(inversedBy: 'routines')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Category $category = null;

    #[ORM\ManyToOne(inversedBy: 'routine')]
    private ?RoutineDay $routineDay = null;

    /**
     * @var Collection<int, UserRoutine>
     */
    #[ORM\OneToMany(targetEntity: UserRoutine::class, mappedBy: 'Routine', orphanRemoval: true)]
    private Collection $userRoutines;

    public function __construct()
    {
        $this->userRoutines = new ArrayCollection();
    }

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

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getRoutineTime(): ?\DateTimeInterface
    {
        return $this->routineTime;
    }

    public function setRoutineTime(\DateTimeInterface $routineTime): static
    {
        $this->routineTime = $routineTime;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;

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

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): static
    {
        $this->category = $category;

        return $this;
    }

    public function getRoutineDay(): ?RoutineDay
    {
        return $this->routineDay;
    }

    public function setRoutineDay(?RoutineDay $routineDay): static
    {
        $this->routineDay = $routineDay;

        return $this;
    }

    /**
     * @return Collection<int, UserRoutine>
     */
    public function getUserRoutines(): Collection
    {
        return $this->userRoutines;
    }

    public function addUserRoutine(UserRoutine $userRoutine): static
    {
        if (!$this->userRoutines->contains($userRoutine)) {
            $this->userRoutines->add($userRoutine);
            $userRoutine->setRoutine($this);
        }

        return $this;
    }

    public function removeUserRoutine(UserRoutine $userRoutine): static
    {
        if ($this->userRoutines->removeElement($userRoutine)) {
            // set the owning side to null (unless already changed)
            if ($userRoutine->getRoutine() === $this) {
                $userRoutine->setRoutine(null);
            }
        }

        return $this;
    }
}
