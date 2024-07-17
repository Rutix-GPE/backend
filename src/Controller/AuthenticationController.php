<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

class AuthenticationController extends AbstractController
{
    #[Route('/user/register', name: 'user_register')]
    public function register(Request $request, UserRepository $userRepository, UserPasswordHasherInterface $passwordHasher): JsonResponse
    {
        $data = $request->getContent();
        $data = json_decode($data, true);

        // return $this->json($data);

        $user = new User;

        if(!isset($data['username']) || 
            !isset($data['firstname']) ||
            !isset($data['lastname']) ||
            !isset($data['email']) || 
            !isset($data['password'])) {
            $response = ["error" => "Missing informations"];
            return $this->json($response, Response::HTTP_BAD_REQUEST);
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
    }


    #[Route('/user/authenticate', name: 'user_authenticate')]
    public function authenticate(Request $request, UserRepository $userRepository, UserPasswordHasherInterface $passwordHasher, JWTTokenManagerInterface $tokenManger): JsonResponse
    {
        $data = $request->getContent();
        $data = json_decode($data, true);

        
        if (!isset($data['password']) || (!isset($data['email']) && !isset($data['username']))) {
            $response = ["error" => "Missing informations"];
            return $this->json($response, Response::HTTP_UNAUTHORIZED);
        }
        
        if(isset($data['email']) && isset($data['username'])){
            $response = ["error" => "Email and Username detected, only one accepted"];
            return $this->json($response, Response::HTTP_UNAUTHORIZED);
        }
        
        if(isset($data['email']))
        {
            $user = $userRepository->findOneBy(['email' => $data['email']]);
        }
        elseif(isset($data['username']))
        {
            $user = $userRepository->findOneBy(['username' => $data['username']]);
        }

        if(!$user){
            $response = ["error" => "Wrong password / username / email"];
            return $this->json($response, Response::HTTP_NOT_FOUND);
        }
        
        if(!$passwordHasher->isPasswordValid($user, $data['password'])){
            $response = ["error" => "Wrong password / username / email"];
            return $this->json($response, Response::HTTP_NOT_FOUND);
        }

        $token = $tokenManger->create($user);

        $response = ["token" => $token];

        return $this->json($response, Response::HTTP_OK);
    }

}
