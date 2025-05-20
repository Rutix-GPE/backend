<?php

namespace App\Service;

// src/Service/AuthService.php
use App\Entity\User;
use App\Dto\Auth\UserLoginDto;
use App\Dto\UserResponseDTO;
use App\Dto\Auth\UserRegisterDTO;
use App\Repository\UserRepository;
<<<<<<< HEAD
use Exception;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
=======
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\DependencyInjection\Attribute\AsService;
>>>>>>> 3776082 (modif authService)


class AuthService
{
    public function __construct(
        private UserRepository $userRepository,
        private UserPasswordHasherInterface $passwordHasher,
        private JWTTokenManagerInterface $jwtManager,
<<<<<<< HEAD
        private readonly SerializerInterface $serializer,
        private readonly ValidatorInterface $validator,
        
    ) {}
    public function controllerRegister($request): UserResponseDTO {
        $dto = $this->serializer->deserialize($request->getContent(), UserRegisterDTO::class, 'json');
        $errors = $this->validator->validate($dto); 
        if (count($errors) > 0) {
            throw new BadRequestHttpException("Données invalides.");
=======

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
>>>>>>> 3776082 (modif authService)
        }
        $user = $this->register($dto);  
        $userDto = new UserResponseDTO($user);
        return ($userDto);
    }
    public function register(UserRegisterDTO $dto): User          
    {
        $existingUser = $this->userRepository->findOneBy([
            'email' => $dto->email,
            'username' => $dto->username,
        ]);
        
        if ($existingUser) {
            throw new BadRequestHttpException("Cet email ou username est déjà utilisé.");
        }   
        $user = new User();
        $user->setUsername($dto->username);
        $user->setFirstname($dto->firstname);
        $user->setLastname(lastname: $dto->lastname);
        $user->setEmail($dto->email);
        $user->setPassword(password: $this->passwordHasher->hashPassword($user, $dto->password));
        $user->setPhonenumber($dto->phonenumber);
        $user->setCountry($dto->country);
        $user->setPostalcode($dto->postalcode);
        $user->setCity($dto->city);
        $user->setAdress($dto->adress);
        $this->userRepository->add($user, true);
        return $user;
        
    }
    public function controllerAuthenticate($request): array {
    
        $dto = $this->serializer->deserialize($request->getContent(), UserLoginDTO::class, 'json');
        //dd($dto);
        $errors = $this->validator->validate($dto); 
        if (count($errors) > 0) {
            throw new BadRequestHttpException("Données invalides.");
        }

        $user = $this->authenticate($dto);
        $token = $this->jwtManager->create($user);
        $userDto = new UserResponseDTO($user);
        return [
            'token' => $token,
            'user' => $userDto
        ];

    }
    public function authenticate(UserLoginDTO $dto): User
    {

        $criteria = $dto->isUsingEmail()
            ? ['email' => $dto->getIdentifier()]
            : ['username' => $dto->getIdentifier()];
    
        $user = $this->userRepository->findOneBy($criteria);

        if (!$user || !$this->passwordHasher->isPasswordValid($user,$dto->password )) {

            throw new UnauthorizedHttpException('Bearer', 'identifiant/email ou mot de passe invalide.');
        }
    
        return $user;
    }

    
}
