<?php

namespace App\Controller;
use App\Service\AuthService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AuthenticationController extends AbstractController
{
    public function __construct(
        private readonly AuthService $authService,
    ) {}
    #[Route('/user/register', name: 'user_register', methods: ['POST'])]
    public function register(Request $request): JsonResponse
    {
        $userDto = $this->authService->controllerRegister($request);
        return $this->json($userDto, Response::HTTP_CREATED);
    }
<<<<<<< Updated upstream
    
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
=======
>>>>>>> Stashed changes
    #[Route('/user/authenticate', name: 'user_authenticate', methods: ['POST'])]
    public function authenticate(Request $request): JsonResponse
    {
        
        $authResult = $this->authService->controllerAuthenticate($request);
        return $this->json([
            'token' => $authResult['token'],
            'user' => get_object_vars($authResult['user']),
        ], Response::HTTP_OK);
    }
    
}
