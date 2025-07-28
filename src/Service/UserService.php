<?php
namespace App\Service;

use App\Entity\User;
use App\Dto\User\UserInputDTO;
use App\Dto\User\UserResponseDTO;

use App\Repository\UserRepository;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


class UserService
{
    public function __construct(
        private UserRepository $userRepository,
        private UserPasswordHasherInterface $passwordHasher,
        private readonly SerializerInterface $serializer,
        private readonly ValidatorInterface $validator,
        
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
        if(!$users){
            throw new NotFoundHttpException("No user found");
        }
        return array_map(fn($user) => new UserResponseDTO($user), $users);
    }


    public function controllerUpdateUser($request, $id): UserResponseDTO {
        $inputDto = $this->serializer->deserialize($request->getContent(), UserInputDTO::class, 'json');
        $errors = $this->validator->validate($inputDto);
        if (count($errors) > 0) {
            throw new BadRequestHttpException("Données invalides.");
        }
        return( new UserResponseDTO($this->updateUser($id, $inputDto)));
    }
    public function updateUser(int $id, UserInputDTO $dto): User
    {
        $user = $this->userRepository->find($id);
        if (!$user) {
            throw new NotFoundHttpException('User not found');
        }
        $newEmail = $dto->email? $dto->email: null;
        $newUsername = $dto->username? $dto->username : null;

    
        // Vérifier l'unicité du nouvel email si modifié
        if ($newEmail && $newEmail !== $user->getEmail()) {
           // throw BadRequestHttpException("test mac ");
            $existingUser = $this->userRepository->findOneBy(['email' => $newEmail]);
            if ($existingUser && $existingUser->getId() !== $user->getId()) {
                throw new BadRequestHttpException("Email déjà utilisé par un autre utilisateur.");
            }
            $user->setEmail($newEmail);
        }

        // Vérifier l'unicité du nouveau username si modifié
        if ($newUsername && $newUsername !== $user->getUsername()) {
           // throw BadRequestHttpException("test mac  username");
            $existingUser = $this->userRepository->findOneBy(['username' => $newUsername]);
            if ($existingUser && $existingUser->getId() !== $user->getId()) {
                throw new BadRequestHttpException("Nom d'utilisateur déjà pris.");
            }
            $user->setUsername($newUsername);
        }
        // Mise à jour conditionnelle
        if ($dto->firstname)    $user->setFirstname($dto->firstname);
        if ($dto->lastname)     $user->setLastname($dto->lastname);
        if ($dto->phonenumber)  $user->setPhonenumber($dto->phonenumber);
        if ($dto->country)      $user->setCountry($dto->country);
        if ($dto->postalcode)   $user->setPostalcode($dto->postalcode);
        if ($dto->city)         $user->setCity($dto->city);
        if ($dto->adress)       $user->setAdress($dto->adress);
        if ($dto->memo)         $user->setMemo($dto->memo);
        if ($dto->avatarFile)    $user->setAvatarFile($dto->avatarFile);
        if ($dto->password) {
            $hashed = $this->passwordHasher->hashPassword($user, $dto->password);
            $user->setPassword($hashed);
        }
        $this->userRepository->add($user, true);
        return $user;
    }
    public function updateUserRole($request, $id){
        $user = $this->userRepository->find($id);
        if (!$user) {
            throw new NotFoundHttpException('User not found');
        }

        $data = json_decode($request->getContent(), true);
        $role = $data['role'] ?? null;
        if (!in_array($role, ['user', 'admin'])) {
            throw new BadRequestHttpException("Le rôle doit être 'user' ou 'admin'.");
        }
        $user->setRoles([$role === 'admin' ? 'ROLE_ADMIN' : 'ROLE_USER']);
        $this->userRepository->add($user, true);
        return $user;
    }
    public function updateMemo($request,$user): User {
        $data = json_decode($request->getContent(), true);
        if (!isset($data['memo'])) {
            throw new BadRequestHttpException("Champ 'memo' manquant.");
        }

        $user->setMemo($data['memo']);
        $this->userRepository->add($user, true);

        return $user;
    }
    public function deleteUser($id): bool {
        $user = $this->userRepository->find($id);
        if (!$user) {
            throw new NotFoundHttpException("Utilisateur introuvable.");
        }

        $this->userRepository->remove($user, true);
        return true;
    }
}
