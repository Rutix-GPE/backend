<?php

namespace App\Entity;

use App\Repository\QuestionV2Repository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: QuestionV2Repository::class)]
class QuestionV2
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50, unique: true)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $content = null;

    #[ORM\Column]
    private ?bool $isRootQuestion = null;

    #[ORM\Column]
    private ?bool $isQuickQuestion = null;

    // /**
    //  * @var Collection<int, RelationV2>
    //  */
    // #[ORM\OneToMany(targetEntity: RelationV2::class, mappedBy: 'source')]
    // private Collection $allSources;

    // /**
    //  * @var Collection<int, RelationV2>
    //  */
    // #[ORM\OneToMany(targetEntity: RelationV2::class, mappedBy: 'targetQuestion')]
    // private Collection $allTargetQuestions;

    // /**
    //  * @var Collection<int, RelationV2>
    //  */
    // #[ORM\OneToMany(targetEntity: RelationV2::class, mappedBy: 'targetRoutine')]
    // private Collection $allTargetRoutines;

    public function __construct()
    {
        $this->allSources = new ArrayCollection();
        $this->allTargetQuestions = new ArrayCollection();
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

    public function isRootQuestion(): ?bool
    {
        return $this->isRootQuestion;
    }

    public function setIsRootQuestion(bool $isRootQuestion): static
    {
        $this->isRootQuestion = $isRootQuestion;

        return $this;
    }

    public function isQuickQuestion(): ?bool
    {
        return $this->isQuickQuestion;
    }

    public function setIsQuickQuestion(bool $isQuickQuestion): static
    {
        $this->isQuickQuestion = $isQuickQuestion;

        return $this;
    }

    // /**
    //  * @return Collection<int, RelationV2>
    //  */
    // public function getSources(): Collection
    // {
    //     return $this->allSources;
    // }

    // public function addSources(RelationV2 $allSources): static
    // {
    //     if (!$this->allSources->contains($allSources)) {
    //         $this->allSources->add($allSources);
    //         $allSources->setSource($this);
    //     }

    //     return $this;
    // }

    // public function removeSources(RelationV2 $allSources): static
    // {
    //     if ($this->allSources->removeElement($allSources)) {
    //         // set the owning side to null (unless already changed)
    //         if ($allSources->getSource() === $this) {
    //             $allSources->setSource(null);
    //         }
    //     }

    //     return $this;
    // }

    // /**
    //  * @return Collection<int, RelationV2>
    //  */
    // public function getAllTargetQuestions(): Collection
    // {
    //     return $this->allTargetQuestions;
    // }

    // public function addAllTargetQuestions(RelationV2 $allTargetQuestions): static
    // {
    //     if (!$this->allTargetQuestions->contains($allTargetQuestions)) {
    //         $this->allTargetQuestions->add($allTargetQuestions);
    //         $allTargetQuestions->setTargetQuestion($this);
    //     }

    //     return $this;
    // }

    // public function removeAllTargetQuestions(RelationV2 $allTargetQuestions): static
    // {
    //     if ($this->allTargetQuestions->removeElement($allTargetQuestions)) {
    //         // set the owning side to null (unless already changed)
    //         if ($allTargetQuestions->getTargetQuestion() === $this) {
    //             $allTargetQuestions->setTargetQuestion(null);
    //         }
    //     }

    //     return $this;
    // }

    // /**
    //  * @return Collection<int, RelationV2>
    //  */
    // public function getAllTargetRoutines(): Collection
    // {
    //     return $this->allTargetRoutines;
    // }

    // public function addAllTargetRoutines(RelationV2 $allTargetRoutines): static
    // {
    //     if (!$this->allTargetRoutines->contains($allTargetRoutines)) {
    //         $this->allTargetRoutines->add($allTargetRoutines);
    //         $allTargetRoutines->setTargetQuestion($this);
    //     }

    //     return $this;
    // }

    // public function removeAllTargetRoutines(RelationV2 $allTargetRoutines): static
    // {
    //     if ($this->allTargetRoutines->removeElement($allTargetRoutines)) {
    //         // set the owning side to null (unless already changed)
    //         if ($allTargetRoutines->getTargetQuestion() === $this) {
    //             $allTargetRoutines->setTargetQuestion(null);
    //         }
    //     }

    //     return $this;
    // }
}
