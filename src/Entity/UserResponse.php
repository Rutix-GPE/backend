<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\UserResponseRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserResponseRepository::class)]
#[ORM\HasLifecycleCallbacks]
#[ApiResource]

class UserResponse
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    public ?int $id = null;

    #[ORM\Column(length: 255)]
    public ?string $name = null;

    #[ORM\Column(type: Types::TEXT)]
    public ?string $content = null;

    #[ORM\Column(length: 50)]
    public ?string $type = null;

    #[ORM\Column(nullable: true)]
    public ?array $choice = null;

    #[ORM\Column(type: Types::SMALLINT)]
    public ?int $page = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    public ?string $response = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    public ?User $User = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    public ?TemplateQuestion $Question = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    public ?\DateTimeInterface $CreationDate = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    public   ?\DateTimeInterface $UpdatedDate = null;

    #[ORM\OneToOne(mappedBy: 'UserResponse', cascade: ['persist', 'remove'])]
    private ?UserRoutine $userRoutine = null;

    public function duplicate(TemplateQuestion $question): static
    {
        $this->name = $question->getName();
        $this->content = $question->getContent();
        $this->type = $question->getType();
        $this->choice = $question->getChoice();
        $this->page = $question->getPage();        
        
        return $this;
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

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): static
    {
        $this->content = $content;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getChoice(): ?array
    {
        return $this->choice;
    }

    public function setChoice(?array $choice): static
    {
        $this->choice = $choice;

        return $this;
    }

    public function getPage(): ?int
    {
        return $this->page;
    }

    public function setPage(int $page): static
    {
        $this->page = $page;

        return $this;
    }

    public function getResponse(): ?string
    {
        return $this->response;
    }

    public function setResponse(?string $response): static
    {
        $this->response = $response;

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

    public function getQuestion(): ?TemplateQuestion
    {
        return $this->Question;
    }

    public function setQuestion(?TemplateQuestion $Question): static
    {
        $this->Question = $Question;

        return $this;
    }

    public function getCreationDate(): ?\DateTimeInterface
    {
        return $this->CreationDate;
    }

    public function setCreationDate(?\DateTimeInterface $CreationDate): static
    {
        $this->CreationDate = $CreationDate;

        return $this;
    }

    public function getUpdatedDate(): ?\DateTimeInterface
    {
        return $this->UpdatedDate;
    }

    public function setUpdatedDate(?\DateTimeInterface $UpdatedDate): static
    {
        $this->UpdatedDate = $UpdatedDate;

        return $this;
    }


    #[ORM\PrePersist]
    public function prePersist()
    {
        $this->CreationDate = new \DateTime();
        $this->UpdatedDate = new \DateTime();
        
        return $this;
    }

    #[ORM\PreUpdate]
    public function preUpdate()
    {
        $this->UpdatedDate = new \DateTime();

        return $this;
    }

    public function getUserRoutine(): ?UserRoutine
    {
        return $this->userRoutine;
    }

    public function setUserRoutine(UserRoutine $userRoutine): static
    {
        // set the owning side of the relation if necessary
        if ($userRoutine->getUserResponse() !== $this) {
            $userRoutine->setUserResponse($this);
        }

        $this->userRoutine = $userRoutine;

        return $this;
    }
}
