<?php

namespace App\Service;

// src/Service/AuthService.php
use App\Entity\User;
use App\Dto\Auth\UserLoginDto;
use App\Dto\UserResponseDTO;
use App\Dto\Auth\UserRegisterDTO;
use App\Repository\UserRepository;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\DependencyInjection\Attribute\AsService;


class AuthService
{
    public function __construct(
        private UserRepository $userRepository,
        private UserPasswordHasherInterface $passwordHasher,
        private JWTTokenManagerInterface $jwtManager,

        private SerializerInterface $serializer,
        private ValidatorInterface $validator
    ) {
        
    }

    public function controllerRegister($request)
    {
        $dto = $this->serializer->deserialize($request->getContent(), UserRegisterDTO::class, 'json');
        $errors = $this->validator->validate($dto);
        if (count($errors) > 0) {
            $errorMessages = [];
            foreach ($errors as $error) {
                $errorMessages[$error->getPropertyPath()] = $error->getMessage();
            }

            return new JsonResponse(['errors' => $errorMessages], JsonResponse::HTTP_BAD_REQUEST);
        }
    
        $user = $this->register($dto);
        $userDto = new UserResponseDTO($user);

        return $userDto;
    }

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
