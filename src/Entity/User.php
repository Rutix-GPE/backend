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
     * @var Collection<int, UserRoutine>
     */
    #[ORM\OneToMany(targetEntity: UserRoutine::class, mappedBy: 'user', orphanRemoval: true)]
    private Collection $userRoutines;

    /**
     * @var Collection<int, UserTask>
     */
    #[ORM\OneToMany(targetEntity: UserTask::class, mappedBy: 'user', orphanRemoval: true)]
    private Collection $userTasks;

    #[ORM\ManyToOne(cascade: ['persist', 'remove'])]
    private ?Question $nextRootQuestion = null;

    public function __construct()
    {
        $this->userRoutines = new ArrayCollection();
        $this->userTasks = new ArrayCollection();
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
     * @return Collection<int, UserRoutine>
     */
    public function getUserRoutines(): Collection
    {
        return $this->userRoutines;
    }

    public function addUserRoutine(UserRoutine $userRoutine): static
    {
        if (!$this->userRoutines->contains($userRoutine)) {
            $this->userRoutines->add($userRoutine);
            $userRoutine->setUser($this);
        }

        return $this;
    }

    public function removeUserRoutine(UserRoutine $userRoutine): static
    {
        if ($this->userRoutines->removeElement($userRoutine)) {
            // set the owning side to null (unless already changed)
            if ($userRoutine->getUser() === $this) {
                $userRoutine->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, UserTask>
     */
    public function getUserTasks(): Collection
    {
        return $this->userTasks;
    }

    public function addUserTask(UserTask $userTask): static
    {
        if (!$this->userTasks->contains($userTask)) {
            $this->userTasks->add($userTask);
            $userTask->setUser($this);
        }

        return $this;
    }

    public function removeUserTask(UserTask $userTask): static
    {
        if ($this->userTasks->removeElement($userTask)) {
            // set the owning side to null (unless already changed)
            if ($userTask->getUser() === $this) {
                $userTask->setUser(null);
            }
        }

        return $this;
    }

    public function getNextRootQuestion(): ?Question
    {
        return $this->nextRootQuestion;
    }

    public function setNextRootQuestion(?Question $nextRootQuestion): static
    {
        $this->nextRootQuestion = $nextRootQuestion;

        return $this;
    }

}
