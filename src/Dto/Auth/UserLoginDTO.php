<?php
namespace App\Dto\Auth;

class UserLoginDTO
{
    public ?string $email = null;
    public ?string $username = null;
    public string $password;

    public function __construct(array $data)
    {
        $this->email = $data['email'] ?? null;
        $this->username = $data['username'] ?? null;
        $this->password = $data['password'] ?? '';

        if (empty($this->password) || (empty($this->email) && empty($this->username))) {
            throw new \InvalidArgumentException('Missing credentials');
        }

        if (!empty($this->email) && !empty($this->username)) {
            throw new \InvalidArgumentException('Provide either email or username, not both.');
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
}
