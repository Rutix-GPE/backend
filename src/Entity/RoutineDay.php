<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\RoutineDayRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RoutineDayRepository::class)]
#[ApiResource]
class RoutineDay
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 20)]
    private ?string $dayOfWeek = null;

    /**
     * @var Collection<int, Routine>
     */
    #[ORM\OneToMany(targetEntity: Routine::class, mappedBy: 'routineDay')]
    private Collection $routine;

    public function __construct()
    {
        $this->routine = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDayOfWeek(): ?string
    {
        return $this->dayOfWeek;
    }

    public function setDayOfWeek(string $dayOfWeek): static
    {
        $this->dayOfWeek = $dayOfWeek;

        return $this;
    }

    /**
     * @return Collection<int, Routine>
     */
    public function getRoutine(): Collection
    {
        return $this->routine;
    }

    public function addRoutine(Routine $routine): static
    {
        if (!$this->routine->contains($routine)) {
            $this->routine->add($routine);
            $routine->setRoutineDay($this);
        }

        return $this;
    }

    public function removeRoutine(Routine $routine): static
    {
        if ($this->routine->removeElement($routine)) {
            // set the owning side to null (unless already changed)
            if ($routine->getRoutineDay() === $this) {
                $routine->setRoutineDay(null);
            }
        }

        return $this;
    }
}
