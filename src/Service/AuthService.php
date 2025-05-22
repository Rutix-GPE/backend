<?php

namespace App\Service;

// src/Service/AuthService.php
use App\Entity\User;
use App\Dto\Auth\UserLoginDTO;
use App\Dto\User\UserResponseDTO;
use App\Dto\Auth\UserRegisterDTO;
use App\Repository\UserRepository;
use Exception;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;


class AuthService
{
    public function __construct(
        private UserRepository $userRepository,
        private UserPasswordHasherInterface $passwordHasher,
        private JWTTokenManagerInterface $jwtManager,
        private readonly SerializerInterface $serializer,
        private readonly ValidatorInterface $validator,
        
    ) {}
    public function controllerRegister($request): UserResponseDTO {
        $dto = $this->serializer->deserialize($request->getContent(), UserRegisterDTO::class, 'json');
        $errors = $this->validator->validate($dto); 
        if (count($errors) > 0) {
            throw new BadRequestHttpException("Données invalides.");
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
   
    public function controllerAuthenticate( $request): array
    {
        $data = json_decode($request->getContent(), true);

        if (!is_array($data)) {
            throw new BadRequestHttpException("Le format du JSON est invalide.");
        }

        // Hydratation manuelle du DTO
        $dto = new UserLoginDTO(
            email: $data['email'] ?? null,
            username: $data['username'] ?? null,
            password: $data['password'] ?? ''
        );

        // Validation
        $errors = $this->validator->validate($dto);
        if (count($errors) > 0) {
            $messages = [];
            foreach ($errors as $error) {
                $messages[] = $error->getPropertyPath() . ' : ' . $error->getMessage();
            }
            throw new BadRequestHttpException(implode("\n", $messages));
        }

        // Authentification
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
