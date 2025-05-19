<?php

namespace App\Controller;

use App\Dto\User\UserResponseDTO;
use App\Dto\User\UserInputDTO;
use App\Dto\User\UserResponseWithRoleDTO;

use App\Repository\UserRepository;
use App\Service\UserService;
use App\Service\AvatarService;

use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class UserController extends AbstractController
{
    public function __construct(
        private readonly SerializerInterface $serializer,
        private readonly ValidatorInterface $validator,
        private readonly UserService $userService,
    ) {}
// pas faire très bien comme ça 
    #[Route('/user/show/{id}', name: 'show_user', methods: ['GET'])]
    public function showUser(int $id): JsonResponse
    {
        $userDTO = $this->userService->getUserById($id);
        return $this->json($userDTO, Response::HTTP_OK);
    }
// pas faire très bien comme ça 
    #[Route('/user/list', name: 'list_user', methods: ['GET'])]
    public function listUser(): JsonResponse
    {
        return $this->json($this->userService->getAllUsers(), Response::HTTP_OK);
    }
// terminé 

    #[Route('/user/update/{id}', name: 'update_user', methods: ['PUT'])]
    public function updateUser(int $id, Request $request): JsonResponse
    {
        $userDTO = $this->userService->controllerUpdateUser($request, $id);
        return $this->json($userDTO, Response::HTTP_OK);
    }

//good 
    #[Route('/user/update-role/{id}', name: 'update_role_user', methods: ['PUT'])]
    public function updateRole(int $id, Request $request, UserRepository $userRepository): JsonResponse
    {
        $user = $this->getUser();
        if (!$user) {
            throw new UnauthorizedHttpException('Bearer', 'Utilisateur non authentifié.');
        }
        if (!in_array('ROLE_ADMIN', $user->getRoles())) {
            throw new UnauthorizedHttpException('acces', "Accès refusé");
        }
       $updatedUser = $this->userService->updateUserRole($request, $id);
        return $this->json(new UserResponseWithRoleDTO($updatedUser), Response::HTTP_OK);
    }
//delete good
    #[Route('/user/delete/{id}', name: 'delete_user', methods: ['DELETE'])]
    public function delete(int $id, UserRepository $userRepository): JsonResponse
    {

        $result = $this->userService->deleteUser($id);
        return $this->json(['success' => $result], Response::HTTP_OK);
    }
//Me elle est faite good good
    #[Route('/user/me', name: 'user_me', methods: ['GET'])]
    public function me(): JsonResponse
    {
        $user = $this->getUser();
        if (!$user) {
            throw new UnauthorizedHttpException('Bearer', 'Utilisateur non authentifié.');
        }

        return $this->json(new UserResponseDTO($user), Response::HTTP_OK);
    }
//Update faite comme il faut
    #[Route('/user/update-memo', name: 'update_memo', methods: ['PUT'])]
    public function updateMemo(Request $request, UserRepository $userRepository): JsonResponse
    {
        $user = $this->getUser();
        if (!$user) {
            throw new UnauthorizedHttpException('Bearer', 'Utilisateur non authentifié.');
        }
        $newUser = $this->userService->updateMemo($request,$user);
        return $this->json(new UserResponseDTO($newUser), Response::HTTP_OK);
    }
}
