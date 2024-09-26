<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: CategoryRepository::class)]
#[ApiResource(
    normalizationContext: ['groups' => ['category:read']]
)]
class Category
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['category:read'])]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    #[Groups(['category:read'])]
    private ?string $description = null;

    /**
     * @var Collection<int, Routine>
     */
    #[ORM\OneToMany(targetEntity: Routine::class, mappedBy: 'category')]
    #[Groups(['category:read'])]
    private Collection $routines;

    /**
     * @var Collection<int, Task>
     */
    #[ORM\OneToMany(targetEntity: Task::class, mappedBy: 'category')]
    private Collection $tasks;

    /**
     * @var Collection<int, ConditionRoutine>
     */
    #[ORM\OneToMany(targetEntity: ConditionRoutine::class, mappedBy: 'category')]
    private Collection $conditionRoutines;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $hexColor = null;

    public function __construct()
    {
        $this->routines = new ArrayCollection();
        $this->tasks = new ArrayCollection();
        $this->conditionRoutines = new ArrayCollection();
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

    /**
     * @return Collection<int, Routine>
     */
    public function getRoutines(): Collection
    {
        return $this->routines;
    }

    public function addRoutine(Routine $routine): static
    {
        if (!$this->routines->contains($routine)) {
            $this->routines->add($routine);
            $routine->setCategory($this);
        }

        return $this;
    }

    public function removeRoutine(Routine $routine): static
    {
        if ($this->routines->removeElement($routine)) {
            // set the owning side to null (unless already changed)
            if ($routine->getCategory() === $this) {
                $routine->setCategory(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Task>
     */
    public function getTasks(): Collection
    {
        return $this->tasks;
    }

    public function addTask(Task $task): static
    {
        if (!$this->tasks->contains($task)) {
            $this->tasks->add($task);
            $task->setCategory($this);
        }

        return $this;
    }

    public function removeTask(Task $task): static
    {
        if ($this->tasks->removeElement($task)) {
            // set the owning side to null (unless already changed)
            if ($task->getCategory() === $this) {
                $task->setCategory(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, ConditionRoutine>
     */
    public function getConditionRoutines(): Collection
    {
        return $this->conditionRoutines;
    }

    public function addConditionRoutine(ConditionRoutine $conditionRoutine): static
    {
        if (!$this->conditionRoutines->contains($conditionRoutine)) {
            $this->conditionRoutines->add($conditionRoutine);
            $conditionRoutine->setCategory($this);
        }

        return $this;
    }

    public function removeConditionRoutine(ConditionRoutine $conditionRoutine): static
    {
        if ($this->conditionRoutines->removeElement($conditionRoutine)) {
            // set the owning side to null (unless already changed)
            if ($conditionRoutine->getCategory() === $this) {
                $conditionRoutine->setCategory(null);
            }
        }

        return $this;
    }

    public function getHexColor(): ?string
    {
        return $this->hexColor;
    }

    public function setHexColor(?string $hexColor): static
    {
        $this->hexColor = $hexColor;

        return $this;
    }
}
