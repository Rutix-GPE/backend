<?php

namespace App\Entity;

use App\Repository\UserTaskRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: UserTaskRepository::class)]
#[ORM\HasLifecycleCallbacks]
class UserTask
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['usertask:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    #[Groups(['usertask:read', 'usertask:write'])]
    private ?string $name = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['usertask:read', 'usertask:write'])]
    private ?string $description = null;

    #[ORM\Column]
    #[Groups(['usertask:read', 'usertask:write'])]
    private ?\DateTime $taskDateTime = null;

    #[ORM\Column(type: Types::BIGINT)]
    #[Groups(['usertask:read', 'usertask:write'])]
    private ?int $status = null;

    #[ORM\ManyToOne(inversedBy: 'userTasks')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Groups(['usertask:read'])]
    private ?\DateTimeInterface $creationDate = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Groups(['usertask:read'])]
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

    public function getTaskDateTime(): ?\DateTime
    {
        return $this->taskDateTime;
    }

    public function setTaskDateTime(\DateTime $taskDateTime): static
    {
        $this->taskDateTime = $taskDateTime;

        return $this;
    }

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(int $status): static
    {
        $this->status = $status;

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
}
