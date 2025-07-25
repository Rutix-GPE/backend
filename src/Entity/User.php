<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_USERNAME', fields: ['username'])]
#[ORM\HasLifecycleCallbacks]
#[ApiResource]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['user:read', 'routine:write'])]
    public ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    public ?string $username = null;

    /**
     * @var list<string> The user roles
     */
    #[ORM\Column]
    public array $roles = ["ROLE_USER"];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    public ?string $password = null;
    // TODO ne pas afficher le mot de passe

    #[ORM\Column(length: 255)]
    public ?string $firstname = null;

    #[ORM\Column(length: 255)]
    public ?string $lastname = null;

    #[ORM\Column(length: 255, unique: true)]
    #[Groups(['user:read'])]
    public ?string $email = null;

    #[ORM\Column(length: 100, nullable: true, unique: true)]
    public ?string $phonenumber = null;

    #[ORM\Column(length: 10, nullable: true)]
    public ?string $country = null;

    #[ORM\Column(length: 100, nullable: true)]
    public ?string $postalcode = null;

    #[ORM\Column(length: 100, nullable: true)]
    public ?string $city = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    public ?string $adress = null;

    #[ORM\Column(length: 255, nullable: true)]
    public ?string $avatarFile = "avatar_par_defaut.png";

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    public ?\DateTimeInterface $CreationDate = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    public ?\DateTimeInterface $UpdatedDate = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    public ?string $Memo = null;

    /**
     * @var Collection<int, Task>
     */
    #[ORM\OneToMany(targetEntity: Task::class, mappedBy: 'User')]
    private Collection $tasks;

    /**
     * @var Collection<int, Routine>
     */
    #[ORM\OneToMany(targetEntity: Routine::class, mappedBy: 'User')]
    private Collection $routines;

    /**
     * @var Collection<int, UserRoutineV2>
     */
    #[ORM\OneToMany(targetEntity: UserRoutineV2::class, mappedBy: 'user', orphanRemoval: true)]
    private Collection $userRoutineV2s;

    /**
     * @var Collection<int, UserTaskV2>
     */
    #[ORM\OneToMany(targetEntity: UserTaskV2::class, mappedBy: 'user', orphanRemoval: true)]
    private Collection $userTaskV2s;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?QuestionV2 $nextRootQuestion = null;

    public function __construct()
    {
        $this->tasks = new ArrayCollection();
        $this->routines = new ArrayCollection();
        $this->userRoutineV2s = new ArrayCollection();
        $this->userTaskV2s = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): static
    {
        $this->username = $username;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->username;
    }

    /**
     * @see UserInterface
     *
     * @return list<string>
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        // $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @param list<string> $roles
     */
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): static
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): static
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getPhonenumber(): ?string
    {
        return $this->phonenumber;
    }

    public function setPhonenumber(?string $phonenumber): static
    {
        $this->phonenumber = $phonenumber;

        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(?string $country): static
    {
        $this->country = $country;

        return $this;
    }

    public function getPostalcode(): ?string
    {
        return $this->postalcode;
    }

    public function setPostalcode(?string $postalcode): static
    {
        $this->postalcode = $postalcode;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(?string $city): static
    {
        $this->city = $city;

        return $this;
    }

    public function getAdress(): ?string
    {
        return $this->adress;
    }

    public function setAdress(?string $adress): static
    {
        $this->adress = $adress;

        return $this;
    }

    public function getAvatarFile(): ?string
    {
        return $this->avatarFile;
    }

    public function setAvatarFile(?string $avatarFile): static
    {
        $this->avatarFile = $avatarFile;

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
    public function getMemo(): ?string
    {
        return $this->Memo;
    }

    public function setMemo(string $Memo): static
    {
        $this->Memo = $Memo;

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
            $task->setUser($this);
        }

        return $this;
    }

    public function removeTask(Task $task): static
    {
        if ($this->tasks->removeElement($task)) {
            // set the owning side to null (unless already changed)
            if ($task->getUser() === $this) {
                $task->setUser(null);
            }
        }

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
            $routine->setUser($this);
        }

        return $this;
    }

    public function removeRoutine(Routine $routine): static
    {
        if ($this->routines->removeElement($routine)) {
            // set the owning side to null (unless already changed)
            if ($routine->getUser() === $this) {
                $routine->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, UserRoutineV2>
     */
    public function getUserRoutineV2s(): Collection
    {
        return $this->userRoutineV2s;
    }

    public function addUserRoutineV2(UserRoutineV2 $userRoutineV2): static
    {
        if (!$this->userRoutineV2s->contains($userRoutineV2)) {
            $this->userRoutineV2s->add($userRoutineV2);
            $userRoutineV2->setUser($this);
        }

        return $this;
    }

    public function removeUserRoutineV2(UserRoutineV2 $userRoutineV2): static
    {
        if ($this->userRoutineV2s->removeElement($userRoutineV2)) {
            // set the owning side to null (unless already changed)
            if ($userRoutineV2->getUser() === $this) {
                $userRoutineV2->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, UserTaskV2>
     */
    public function getUserTaskV2s(): Collection
    {
        return $this->userTaskV2s;
    }

    public function addUserTaskV2(UserTaskV2 $userTaskV2): static
    {
        if (!$this->userTaskV2s->contains($userTaskV2)) {
            $this->userTaskV2s->add($userTaskV2);
            $userTaskV2->setUser($this);
        }

        return $this;
    }

    public function removeUserTaskV2(UserTaskV2 $userTaskV2): static
    {
        if ($this->userTaskV2s->removeElement($userTaskV2)) {
            // set the owning side to null (unless already changed)
            if ($userTaskV2->getUser() === $this) {
                $userTaskV2->setUser(null);
            }
        }

        return $this;
    }

    public function getNextRootQuestion(): ?QuestionV2
    {
        return $this->nextRootQuestion;
    }

    public function setNextRootQuestion(?QuestionV2 $nextRootQuestion): static
    {
        $this->nextRootQuestion = $nextRootQuestion;

        return $this;
    }

}
