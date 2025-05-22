<?php

namespace App\Entity;

use App\Repository\RelationV2Repository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RelationV2Repository::class)]
class RelationV2
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    public function getId(): ?int
    {
        return $this->id;
    }
}
