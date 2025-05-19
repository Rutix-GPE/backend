<?php
namespace App\Service;

use App\Repository\UserRepository;
use App\Dto\UserResponseDTO;
use App\Dto\UserInputDTO;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


class UserService
{
    public function __construct(
        private UserRepository $userRepository,
        private UserPasswordHasherInterface $passwordHasher,

    ) {}

    public function getUserById(int $id): UserResponseDTO
    {
        $user = $this->userRepository->find($id);

        if (!$user) {
            throw new NotFoundHttpException("User not found");
        }

        return new UserResponseDTO($user);
    }

    public function getAllUsers(): array
    {
        $users = $this->userRepository->findAll();

        return array_map(fn($user) => new UserResponseDTO($user), $users);
    }

    public function updateUser(int $id, UserInputDTO $dto): UserResponseDTO
    {
        $user = $this->userRepository->find($id);
        if (!$user) {
            throw new NotFoundHttpException('User not found');
        }
        // Mise à jour conditionnelle
        if ($dto->username)     $user->setUsername($dto->username);
        if ($dto->firstname)    $user->setFirstname($dto->firstname);
        if ($dto->lastname)     $user->setLastname($dto->lastname);
        if ($dto->email)        $user->setEmail($dto->email);
        if ($dto->phonenumber)  $user->setPhonenumber($dto->phonenumber);
        if ($dto->country)      $user->setCountry($dto->country);
        if ($dto->postalcode)   $user->setPostalcode($dto->postalcode);
        if ($dto->city)         $user->setCity($dto->city);
        if ($dto->adress)       $user->setAdress($dto->adress);
        if ($dto->memo)         $user->setMemo($dto->memo);
        if ($dto->password) {
            $hashed = $this->passwordHasher->hashPassword($user, $dto->password);
            $user->setPassword($hashed);
        }

        $this->userRepository->add($user, true);

        return new UserResponseDTO($user);
    }
}
