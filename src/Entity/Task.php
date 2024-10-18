<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\TaskRepository;
use DateTime;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: TaskRepository::class)]
#[ORM\HasLifecycleCallbacks]
#[ApiResource(
    normalizationContext: ['groups' => ['task:read']],
    denormalizationContext: ['groups' => ['task:write']]
)]
class Task
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['task:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['task:read', 'task:write'])]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Groups(['task:read', 'task:write'])]
    private ?string $description = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Groups(['task:read', 'task:write'])]
    private ?\DateTimeInterface $taskDate = null;

    #[ORM\Column(type: Types::TIME_MUTABLE)]
    #[Groups(['task:read', 'task:write'])]
    private ?\DateTimeInterface $taskTime = null;

    #[ORM\Column(length: 50)]
    #[Groups(['task:read', 'task:write'])]
    private ?string $status = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $creationDate = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $updatedDate = null;

    #[ORM\ManyToOne(inversedBy: 'tasks')]
    #[ORM\JoinColumn(nullable: true)]
    private ?Category $category = null;

    #[ORM\ManyToOne(inversedBy: 'tasks')]
    #[Groups(['task:read', 'task:write'])]
    private ?User $User = null;

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

    public function getTaskDate(): ?\DateTimeInterface
    {
        return $this->taskDate;
    }

    public function setTaskDate(\DateTimeInterface $taskDate): static
    {
        $this->taskDate = $taskDate;

        return $this;
    }

    public function getTaskTime(): ?\DateTimeInterface
    {
        return $this->taskTime;
    }

    public function setTaskTime(\DateTimeInterface $taskTime): static
    {
        $this->taskTime = $taskTime;

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

    public function getUser(): ?User
    {
        return $this->User;
    }

    public function setUser(?User $User): static
    {
        $this->User = $User;

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

    public function createList(Routine $routine, TaskRepository $taskRepository)
    {
        $today = new DateTime();

        $nextWeek = new DateTime('+1 week');

        while ($today <= $nextWeek) {
            $todayF = $today->format('N');

            if( in_array($todayF, $routine->getDays()) ) {

                $this->createOne($routine, $today->format('Y-m-d'), $taskRepository);
            } 

            $today->modify('+1 day'); 
        }
    }

    public function createOne(Routine $routine, $date, TaskRepository $taskRepository)
    {
        $task = new Task;

        $task->setName($routine->getName());
        $task->setDescription($routine->getDescription());
        $task->setTaskTime($routine->getTaskTime());
        if (is_string($date)) {
            $date = DateTime::createFromFormat('Y-m-d', $date); // Adapter le format à ton besoin (par exemple 'Y-m-d')
        }
        $task->setTaskDate($date);
        $task->setStatus("Not finish");
        $task->setUser($routine->getUser());

        $taskRepository->add($task, true);
    }
}
