<?php

namespace App\Controller;
use App\Entity\User;
use App\Repository\UserRepository;
use App\Dto\Auth\UserRegisterDTO;
use App\Dto\Auth\UserLoginDTO;
use App\Dto\UserResponseDTO;
use App\Service\RegisterService;
use App\Service\AuthService;

use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class AuthenticationController extends AbstractController
{
    #[Route('/user/register', name: 'user_register', methods: ['POST'])]
    public function register(
        Request $request,
        SerializerInterface $serializer,
        ValidatorInterface $validator,
        RegisterService $registerService
    ): JsonResponse
    {
        try {
            /** @var ConstraintViolationListInterface $errors */
            $dto = $serializer->deserialize($request->getContent(), UserRegisterDTO::class, 'json');
            $errors = $validator->validate($dto);

            $errors = $validator->validate($dto);

            if (count($errors) > 0) {
                $errorMessages = [];
                foreach ($errors as $error) {
                    $errorMessages[$error->getPropertyPath()] = $error->getMessage();
                }
    
                return new JsonResponse(['errors' => $errorMessages], JsonResponse::HTTP_BAD_REQUEST);
            }
        
            $user = $registerService->register($dto);
            $userDto = new UserResponseDTO($user);
    
            return $this->json($userDto, Response::HTTP_CREATED);
    
        } catch (\Throwable $e) {
            return $this->json(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    
    /*
    #[Route('/user/register', name: 'user_register')]
    public function register(Request $request, UserRepository $userRepository, UserPasswordHasherInterface $passwordHasher): JsonResponse
    {
        $data = $request->getContent();
        $data = json_decode($data, true);

        $user = new User;

        if(!isset($data['username']) || 
            !isset($data['firstname']) ||
            !isset($data['lastname']) ||
            !isset($data['email']) || 
            !isset($data['password'])) {
            $response = ["error" => "Missing informations"];
            return $this->json($response, Response::HTTP_BAD_REQUEST);
        }

        $duplicateUsername = $userRepository->findBy([
            'username' => $data['username']
        ]);
        if($duplicateUsername) {
            return $this->json(['error' => 'Duplicate'], Response::HTTP_CONFLICT);
        }

        $duplicateEmail = $userRepository->findBy([
            'email' => $data['email']
        ]);
        if($duplicateEmail) {
            return $this->json(['error' => 'Duplicate'], Response::HTTP_CONFLICT);
        }

        if(isset($data['phonenumber'])){
            $duplicatePhoneNumber = $userRepository->findBy([
                'phonenumber' => $data['phonenumber']
            ]);
            if($duplicatePhoneNumber) {
                return $this->json(['error' => 'Duplicate'], Response::HTTP_CONFLICT);
            }
        }

        try {
            $user = new User;

            $hashedPassword = $passwordHasher->hashPassword(
                $user,
                $data['password']
            );

            $user->setUsername($data['username']);
            $user->setFirstname($data['firstname']);
            $user->setLastname($data['lastname']);
            $user->setEmail($data['email']);
            $user->setPassword($hashedPassword);

            if(isset($data['phonenumber'])){
                $user->setPhonenumber($data['phonenumber']);
            }
            if(isset($data['country'])){
                $user->setCountry($data['country']);
            }
            if(isset($data['postalcode'])){
                $user->setPostalcode($data['postalcode']);
            }
            if(isset($data['city'])){
                $user->setCity($data['city']);
            }
            if(isset($data['adress'])){
                $user->setAdress($data['adress']);
            }

            $userRepository->add($user, true);

            return $this->json($user, Response::HTTP_CREATED);

        } catch (\Exception $error) {
            $response = ["error" => $error->getMessage()];
            return $this->json($response, Response::HTTP_BAD_REQUEST);
        }
    }*/
    #[Route('/user/authenticate', name: 'user_authenticate', methods: ['POST'])]
    public function authenticate(Request $request, AuthService $authService): JsonResponse
    {
        try {
            $data = json_decode($request->getContent(), true);
            $dto = new UserLoginDTO($data);

            $authResult = $authService->authenticate($dto);

            return $this->json([
                'token' => $authResult['token'],
                'user' => get_object_vars($authResult['user']) // ou $this->normalizer->normalize(...)
            ], Response::HTTP_OK);
        } catch (\InvalidArgumentException $e) {
            return $this->json(["error" => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        } catch (\Exception $e) {
            return $this->json(["error" => "Invalid email/username or password"], Response::HTTP_UNAUTHORIZED);
        }
    }
}
