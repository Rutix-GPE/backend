<?php

namespace App\Service;

use App\Entity\User;
use App\Dto\Auth\UserRegisterDTO;
use App\Dto\UserResponseDTO;
use App\Repository\UserRepository;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class RegisterService
{
    public function __construct(
        private UserRepository $userRepository,
        private UserPasswordHasherInterface $passwordHasher
    ) {}

    public function register(UserRegisterDTO $dto): User
    {
        $user = new User();
        $user->setUsername($dto->username);
        $user->setFirstname($dto->firstname);
        $user->setLastname($dto->lastname);
        $user->setEmail($dto->email);
        $user->setPassword($this->passwordHasher->hashPassword($user, $dto->password));

        $user->setPhonenumber($dto->phonenumber);
        $user->setCountry($dto->country);
        $user->setPostalcode($dto->postalcode);
        $user->setCity($dto->city);
        $user->setAdress($dto->adress);

        $this->userRepository->add($user, true);

        return $user;
    }
}
