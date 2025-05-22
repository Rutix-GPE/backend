<?php 
namespace App\Dto\Auth;
use Assert\Callback;
use Assert\NotBlank;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

  class UserLoginDto
{
    public function __construct(
        public ?string $email = null,
        public ?string $username = null,
        #[NotBlank(message: "Le mot de passe est requis.")]
        public string $password = ''
    ) {}

    #[Callback]
    public function validate(ExecutionContextInterface $context): void
    {
        if (empty($this->email) && empty($this->username)) {
            $context->buildViolation("L'email ou le nom d'utilisateur est requis.")
                ->atPath('email')
                ->addViolation();
        }

        if ($this->email && !filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            $context->buildViolation("Format de l'email invalide.")
                ->atPath('email')
                ->addViolation();
        }

        if ($this->username && !preg_match('/^[a-zA-Z0-9]+$/', $this->username)) {
            $context->buildViolation("Le nom d'utilisateur doit être alphanumérique.")
                ->atPath('username')
                ->addViolation();
        }
    }

    public function isUsingEmail(): bool
    {
        return !empty($this->email);
    }

    public function getIdentifier(): string
    {
        return $this->email ?? $this->username;
    }

    public function getPassword(): string
    {
        return $this->password;
    }
}
