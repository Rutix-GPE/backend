<?php

namespace App\Entity;

use App\Repository\TemplateQuestionRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TemplateQuestionRepository::class)]
class TemplateQuestion
{

// TODO add page field 

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
}
