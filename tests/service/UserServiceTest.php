<?php

namespace App\Tests\Service;

use App\Service\UserService;
use App\Entity\User;
use App\Dto\User\UserInputDTO;
use App\Dto\User\UserResponseDTO;
use App\Repository\UserRepository;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class UserServiceTest extends TestCase
{
    private $userRepository;
    private $passwordHasher;
    private $serializer;
    private $validator;
    private $service;

    protected function setUp(): void
    {
        $this->userRepository = $this->createMock(UserRepository::class);
        $this->passwordHasher = $this->createMock(UserPasswordHasherInterface::class);
        $this->serializer = $this->createMock(SerializerInterface::class);
        $this->validator = $this->createMock(ValidatorInterface::class);

        $this->service = new UserService(
            $this->userRepository,
            $this->passwordHasher,
            $this->serializer,
            $this->validator
        );
    }

    private function setUserId(User $user, $id)
    {
        $ref = new \ReflectionClass($user);
        $prop = $ref->getProperty('id');
        $prop->setAccessible(true);
        $prop->setValue($user, $id);
    }

    private function createValidUser($id = 1)
    {
        $user = new User();
        $this->setUserId($user, $id);
        $user->setUsername("user$id");
        $user->setEmail("user$id@example.com");
        $user->setFirstname("Firstname$id");
        $user->setLastname("Lastname$id");
        $user->setPassword('password');
        return $user;
    }

    public function testGetUserByIdReturnsDto()
    {
        $user = $this->createValidUser(1);

        $this->userRepository->method('find')->willReturn($user);

        $dto = $this->service->getUserById(1);
        $this->assertInstanceOf(UserResponseDTO::class, $dto);
        $this->assertEquals(1, $dto->id);
        $this->assertEquals('user1', $dto->username);
    }

    public function testGetUserByIdThrowsIfNotFound()
    {
        $this->userRepository->method('find')->willReturn(null);
        $this->expectException(NotFoundHttpException::class);
        $this->service->getUserById(999);
    }

    public function testGetAllUsersReturnsDtoArray()
    {
        $user = $this->createValidUser(42);

        $this->userRepository->method('findAll')->willReturn([$user]);
        $result = $this->service->getAllUsers();
        $this->assertIsArray($result);
        $this->assertInstanceOf(UserResponseDTO::class, $result[0]);
        $this->assertEquals(42, $result[0]->id);
        $this->assertEquals('user42', $result[0]->username);
    }

    public function testGetAllUsersThrowsIfNone()
    {
        $this->userRepository->method('findAll')->willReturn([]);
        $this->expectException(NotFoundHttpException::class);
        $this->service->getAllUsers();
    }

    public function testControllerUpdateUserWorks()
    {
        $inputDto = new UserInputDTO();
        $inputDto->firstname = "John";
        $inputDto->lastname = "Doe";
        $inputDto->username = "johnny";
        $inputDto->email = "john@example.com";

        $requestMock = $this->createMock(Request::class);
        $requestMock->method('getContent')->willReturn(json_encode([
            'firstname' => 'John',
            'lastname' => 'Doe',
            'username' => 'johnny',
            'email' => 'john@example.com',
        ]));

        $user = $this->createValidUser(88);

        // Mock validate returns 0 violations
        $violationList = $this->createMock(ConstraintViolationListInterface::class);
        $violationList->method('count')->willReturn(0);
        $this->validator->method('validate')->willReturn($violationList);

        $this->serializer->method('deserialize')->willReturn($inputDto);
        $this->userRepository->method('find')->willReturn($user);
        $this->userRepository->method('findOneBy')->willReturn(null);

        $this->userRepository->expects($this->once())->method('add');

        $dto = $this->service->controllerUpdateUser($requestMock, 88);
        $this->assertInstanceOf(UserResponseDTO::class, $dto);
    }

    public function testControllerUpdateUserThrowsOnInvalidData()
    {
        $inputDto = new UserInputDTO();
        $requestMock = $this->createMock(Request::class);
        $requestMock->method('getContent')->willReturn('{}');

        $violationList = $this->createMock(ConstraintViolationListInterface::class);
        $violationList->method('count')->willReturn(1);
        $this->validator->method('validate')->willReturn($violationList);

        $this->serializer->method('deserialize')->willReturn($inputDto);

        $this->expectException(BadRequestHttpException::class);
        $this->service->controllerUpdateUser($requestMock, 1);
    }

    public function testUpdateUserThrowsIfUserNotFound()
    {
        $this->userRepository->method('find')->willReturn(null);
        $dto = new UserInputDTO();
        $this->expectException(NotFoundHttpException::class);
        $this->service->updateUser(1, $dto);
    }

    public function testUpdateUserThrowsIfEmailAlreadyUsed()
    {
        $user = $this->createValidUser(3);
        $user->setEmail("old@example.com");

        $this->userRepository->method('find')->willReturn($user);

        $dto = new UserInputDTO();
        $dto->email = "used@example.com";

        $usedUser = $this->createValidUser(4);
        $usedUser->setEmail("used@example.com");

        $this->userRepository->method('findOneBy')->with(['email' => 'used@example.com'])->willReturn($usedUser);

        $this->expectException(BadRequestHttpException::class);
        $this->service->updateUser(3, $dto);
    }

    public function testUpdateUserThrowsIfUsernameAlreadyUsed()
    {
        $user = $this->createValidUser(3);
        $user->setUsername("oldjohn");

        $this->userRepository->method('find')->willReturn($user);

        $dto = new UserInputDTO();
        $dto->username = "usedjohn";

        $usedUser = $this->createValidUser(5);
        $usedUser->setUsername("usedjohn");

        $this->userRepository->method('findOneBy')->with(['username' => 'usedjohn'])->willReturn($usedUser);

        $this->expectException(BadRequestHttpException::class);
        $this->service->updateUser(3, $dto);
    }

    public function testUpdateUserSetsPasswordIfProvided()
    {
        $user = $this->createValidUser(10);

        $this->userRepository->method('find')->willReturn($user);

        $dto = new UserInputDTO();
        $dto->password = "newpassword";

        $this->passwordHasher->method('hashPassword')->willReturn('hashed');
        $this->userRepository->method('findOneBy')->willReturn(null);

        $this->userRepository->expects($this->once())->method('add');

        $res = $this->service->updateUser(10, $dto);
        $this->assertInstanceOf(User::class, $res);
    }

    public function testUpdateUserRoleWorks()
    {
        $user = $this->createValidUser(99);

        $this->userRepository->method('find')->willReturn($user);

        $requestMock = $this->createMock(Request::class);
        $requestMock->method('getContent')->willReturn(json_encode(['role' => 'admin']));

        $this->userRepository->expects($this->once())->method('add');
        $res = $this->service->updateUserRole($requestMock, 99);
        $this->assertInstanceOf(User::class, $res);
        $this->assertEquals(['ROLE_ADMIN'], $user->getRoles());
    }

    public function testUpdateUserRoleThrowsOnInvalidRole()
    {
        $user = $this->createValidUser(77);

        $this->userRepository->method('find')->willReturn($user);

        $requestMock = $this->createMock(Request::class);
        $requestMock->method('getContent')->willReturn(json_encode(['role' => 'hacker']));

        $this->expectException(BadRequestHttpException::class);
        $this->service->updateUserRole($requestMock, 77);
    }

    public function testUpdateMemoWorks()
    {
        $user = $this->createValidUser(33);

        $requestMock = $this->createMock(Request::class);
        $requestMock->method('getContent')->willReturn(json_encode(['memo' => 'My note']));

        $this->userRepository->expects($this->once())->method('add');
        $res = $this->service->updateMemo($requestMock, $user);
        $this->assertInstanceOf(User::class, $res);
        $this->assertEquals('My note', $user->getMemo());
    }

    public function testUpdateMemoThrowsOnMissingField()
    {
        $user = $this->createValidUser(33);

        $requestMock = $this->createMock(Request::class);
        $requestMock->method('getContent')->willReturn(json_encode([]));

        $this->expectException(BadRequestHttpException::class);
        $this->service->updateMemo($requestMock, $user);
    }

    public function testDeleteUserWorks()
    {
        $user = $this->createValidUser(123);

        $this->userRepository->method('find')->willReturn($user);
        $this->userRepository->expects($this->once())->method('remove');

        $this->assertTrue($this->service->deleteUser(123));
    }

    public function testDeleteUserThrowsIfNotFound()
    {
        $this->userRepository->method('find')->willReturn(null);
        $this->expectException(NotFoundHttpException::class);
        $this->service->deleteUser(777);
    }
}

