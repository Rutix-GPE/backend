<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Service\AvatarService;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserController extends AbstractController
{
    #[Route('/user/show/{id}', name: 'show_user', methods: ['GET'])]
    public function showUser($id, UserRepository $userRepository): JsonResponse
    {
        $user = $userRepository->findOneBy(['id' => $id]);

        if(!$user){
            $response = ["msg" => "Not found"];
            return $this->json($response, Response::HTTP_NOT_FOUND);
        }

        return $this->json($user, Response::HTTP_OK);
    }

    #[Route('/user/list', name: 'list_user', methods: ['GET'])]
    public function listUser(UserRepository $userRepository): JsonResponse
    {
        $users = $userRepository->findAll();

        if(!$users){
            $response = ["msg" => "Zero users"];
            return $this->json($response, Response::HTTP_NOT_FOUND);
        }

        return $this->json($users, Response::HTTP_OK);
    }

    #[Route('/user/update/{id}', name: 'update_user', methods: ['PUT'])]
    public function updateUser($id, Request $request, UserRepository $userRepository, UserPasswordHasherInterface $passwordHasher): JsonResponse
    {
        $user = $userRepository->findOneBy(['id' => $id]);

        if(!$user){
            $response = ["msg" => "Not found"];
            return $this->json($response, Response::HTTP_NOT_FOUND);
        }

        $data = $request->getContent();
        $data = json_decode($data, true);        
        
        try{
            if(isset($data['username'])){
                $user->setUsername($data['username']);
            }
            if(isset($data['password'])){
                $hashedPassword = $passwordHasher->hashPassword(
                    $user,
                    $data['password']
                );

                $user->setPassword($hashedPassword);
            }
            if(isset($data['firstname'])){
                $user->setFirstname($data['firstname']);
            }
            if(isset($data['lastname'])){
                $user->setLastname($data['lastname']);
            }
            if(isset($data['email'])){
                $user->setEmail($data['email']);
            }
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

            return $this->json($user, Response::HTTP_OK);
        } catch (\Exception $error) {
            $response = ["error" => $error->getMessage()];
            return $this->json($response, Response::HTTP_BAD_REQUEST);
        }
    }

    #[Route('/user/update-role/{id}', name: 'update_role_user', methods: ['PUT'])]
    public function updateRole($id, Request $request, UserRepository $userRepository): JsonResponse
    {
        $user = $userRepository->findOneBy(['id' => $id]);

        if(!$user){
            $response = ["msg" => "Not found"];
            return $this->json($response, Response::HTTP_NOT_FOUND);
        }

        $data = $request->getContent();
        $data = json_decode($data, true);  

        if(!isset($data["role"])){
            $response = ["msg" => "Choose beetwen role user or admin"];
            return $this->json($response, Response::HTTP_NOT_FOUND);
        }
        
        $role = $data["role"];

        if(!in_array($role, ['user', 'admin'])){
            $response = ["msg" => "Choose beetwen role user or admin"];
            return $this->json($response, Response::HTTP_NOT_FOUND);
        }

        if($role == "user"){
            $user->setRoles(["ROLE_USER"]);
        } elseif($role == "admin"){
            $user->setRoles(["ROLE_ADMIN"]);
        }

        $userRepository->add($user, true);

        return $this->json($user);
    }

    #[Route('/user/update-avatar/{avatar}', name: 'update_avatar', methods: ['PUT'])]
    public function updateAvatarV1($avatar, Request $request, UserRepository $userRepository, AvatarService $avatarService): JsonResponse
    {
        $user = $this->getUser();
        $projectDir = $this->getParameter('kernel.project_dir');

        if(!$avatarService->checkExistAvatarFile($avatar, $projectDir)){
            return $this->json("File not found", Response::HTTP_NOT_FOUND);
        }
        
        $user->setAvatarFile($avatar);
        $userRepository->add($user, true);

        return $this->json($user);
    }

    #[Route('/user/delete/{id}', name: 'delete_user', methods: ['DELETE'])]
    public function delete($id, Request $request, UserRepository $userRepository): JsonResponse
    {
        $user = $userRepository->findOneBy(['id' => $id]);

        if(!$user){
            $response = ["msg" => "Not found"];
            return $this->json($response, Response::HTTP_NOT_FOUND);
        }

        $userRepository->remove($user, true);

        $user = $userRepository->findOneBy(['id' => $id]);

        if($user){
            $response = ["success" => false];
            return $this->json($response, Response::HTTP_INTERNAL_SERVER_ERROR);
        } else {
            $response = ["success" => true];
            return $this->json($response, Response::HTTP_OK);
        }
    }

    #[Route('/user/me', name: 'user_me', methods: ['GET'])]
    public function me(): JsonResponse
    {
        $user = $this->getUser();

        if (!$user) {
            return new JsonResponse(['msg' => 'Not authenticated'], Response::HTTP_UNAUTHORIZED);
        }

        return $this->json($user, Response::HTTP_OK);
    }
    #[Route('/user/update-memo', name: 'update_memo', methods: ['PUT'])]
    public function updateMemo(Request $request, UserRepository $userRepository, JWTTokenManagerInterface $tokenManager): JsonResponse
    {
        $user = $this->getUser();
        if (!$user) {
            return new JsonResponse(['msg' => 'Not authenticated'], Response::HTTP_UNAUTHORIZED);
        }

        $data = $request->getContent();
        $data = json_decode($data, true);  


        try{
            $user->setMemo($data['memo']);

            $userRepository->add($user, true);

            return $this->json($user, Response::HTTP_OK);
        } catch (\Exception $error) {
            $response = ["error" => $error->getMessage()];

            return $this->json($response, Response::HTTP_BAD_REQUEST);
        }
    }  
    

}
