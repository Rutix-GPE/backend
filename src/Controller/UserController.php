<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Service\AvatarService;
use App\Dto\UserResponseDTO;
use App\Dto\UserInputDTO;
use App\Service\UserService;

use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Serializer\SerializerInterface;




class UserController extends AbstractController
{
    #[Route('/user/show/{id}', name: 'show_user', methods: ['GET'])]
    public function showUser(int $id, UserService $userService): JsonResponse
    {
        try {
            $userDTO = $userService->getUserById($id);
            return $this->json($userDTO, Response::HTTP_OK);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], Response::HTTP_NOT_FOUND);
        }
    }

    #[Route('/user/list', name: 'list_user', methods: ['GET'])]
    public function listUser(UserService $userService): JsonResponse
    {
        $usersDTO = $userService->getAllUsers();
        return $this->json($usersDTO, Response::HTTP_OK);
    }
    #[Route('/user/update/{id}', name: 'update_user', methods: ['PUT'])]
    public function updateUser(
        int $id,
        Request $request, 
        UserService $userService,
        ValidatorInterface $validator,
        SerializerInterface $serializer,

    ): JsonResponse
    {
        try {
            $inputDto = $serializer->deserialize($request->getContent(), UserInputDTO::class, 'json');
            $errors = $validator->validate($inputDto);

            if (count($errors) > 0) {
                $errorMessages = [];
                foreach ($errors as $error) {
                    $errorMessages[$error->getPropertyPath()] = $error->getMessage();
                }
    
                return new JsonResponse(['errors' => $errorMessages], JsonResponse::HTTP_BAD_REQUEST);
            }

            $userDTO = $userService->updateUser($id, $inputDto);
            return $this->json($userDTO, Response::HTTP_OK);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
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

    #[Route('/user/update-avatar', name: 'update_avatar', methods: ['PATCH'])]
    public function updateAvatarV1(Request $request, UserRepository $userRepository, AvatarService $avatarService): JsonResponse
    {
        $user = $this->getUser();
        $projectDir = $this->getParameter('kernel.project_dir');

        $data = $request->getContent();
        $data = json_decode($data, true);  

        if(!isset($data['avatar'])){
            return $this->json("Missing informations", Response::HTTP_NOT_FOUND);
        }
        
        $avatar = $data['avatar'];

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
