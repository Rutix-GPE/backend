<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

class AuthenticationController extends AbstractController
{
    #[Route('/user/register', name: 'user_register')]
    public function register(Request $request, UserPasswordHasherInterface $passwordHasher): JsonResponse
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


            return $this->json($user);

        } catch (\Exception $error) {
            $response = ["error" => $error->getMessage()];
            return $this->json($response, Response::HTTP_BAD_REQUEST);
        }

        // $user->setUsername($data['username']);
        // $user->setFirstname($data['firstname']);
        // $user->setLastname($data['lastname']);
        // $user->setPassword($data['password']);


        return $this->json($data);
    }
}
