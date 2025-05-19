<?php

namespace App\Service;

// src/Service/AuthService.php
use App\Entity\User;
use App\Dto\Auth\UserLoginDto;
use App\Dto\UserResponseDTO;
use App\Repository\UserRepository;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;



class AuthService
{
    public function __construct(
        private UserRepository $userRepository,
        private UserPasswordHasherInterface $passwordHasher,
        private JWTTokenManagerInterface $jwtManager
    ) {}

    public function authenticate(UserLoginDTO $dto): array
    {
        $criteria = $dto->isUsingEmail()
            ? ['email' => $dto->getIdentifier()]
            : ['username' => $dto->getIdentifier()];

        $user = $this->userRepository->findOneBy($criteria);

        if (!$user || !$this->passwordHasher->isPasswordValid($user, $dto->password)) {
            throw new \Exception("Invalid credentials");
        }

        $token = $this->jwtManager->create($user);
        $userDto = new UserResponseDTO($user);

        return [
            'token' => $token,
            'user' => $userDto
        ];
    }
    
}
