<?php

namespace App\Entity;

use App\Repository\RoutineV2Repository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RoutineV2Repository::class)]
class RoutineV2
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

    /**
     * @var Collection<int, RelationV2>
     */
    #[ORM\OneToMany(targetEntity: RelationV2::class, mappedBy: 'targetRoutine')]
    private Collection $relationV2s;

    public function __construct()
    {
        $this->relationV2s = new ArrayCollection();
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

    /**
     * @return Collection<int, RelationV2>
     */
    public function getRelationV2s(): Collection
    {
        return $this->relationV2s;
    }

    public function addRelationV2(RelationV2 $relationV2): static
    {
        if (!$this->relationV2s->contains($relationV2)) {
            $this->relationV2s->add($relationV2);
            $relationV2->setTargetRoutine($this);
        }

        return $this;
    }

    public function removeRelationV2(RelationV2 $relationV2): static
    {
        if ($this->relationV2s->removeElement($relationV2)) {
            // set the owning side to null (unless already changed)
            if ($relationV2->getTargetRoutine() === $this) {
                $relationV2->setTargetRoutine(null);
            }
        }

        return $this;
    }
}
