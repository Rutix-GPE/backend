<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\TemplateQuestionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TemplateQuestionRepository::class)]
#[ORM\HasLifecycleCallbacks]
#[ApiResource]
class TemplateQuestion
{

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    public ?int $id = null;

    #[ORM\Column(length: 255, unique: true)]
    public ?string $name = null;

    #[ORM\Column(type: Types::TEXT)]
    public ?string $content = null;

    #[ORM\Column(length: 50)]
    public ?string $type = null;

    #[ORM\Column(nullable: true)]
    public ?array $choice = null;

    #[ORM\Column(type: Types::SMALLINT)]
    public ?int $page = 1;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    public ?\DateTimeInterface $CreationDate = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    public ?\DateTimeInterface $UpdatedDate = null;

    /**
     * @var Collection<int, ConditionRoutine>
     */
    #[ORM\OneToMany(targetEntity: ConditionRoutine::class, mappedBy: 'Question')]
    private Collection $conditionRoutines;

    public function __construct()
    {
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


    CONST TYPE_ARRAY = [
        self::CODE_TEXT => self::LABEL_TEXT,
        self::CODE_MULTIPLE_CHOICE => self::LABEL_MULTIPLE_CHOICE
    ];

    const CODE_TEXT = 1000;
    CONST LABEL_TEXT = "text";

    const CODE_MULTIPLE_CHOICE = 2000;
    CONST LABEL_MULTIPLE_CHOICE = "multiple_choice";

    public function getPage(): ?int
    {
        return $this->page;
    }

    public function setPage(int $page): static
    {
        $this->page = $page;

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
            $conditionRoutine->setQuestion($this);
        }

        return $this;
    }

    public function removeConditionRoutine(ConditionRoutine $conditionRoutine): static
    {
        if ($this->conditionRoutines->removeElement($conditionRoutine)) {
            // set the owning side to null (unless already changed)
            if ($conditionRoutine->getQuestion() === $this) {
                $conditionRoutine->setQuestion(null);
            }
        }

        return $this;
    }
}
