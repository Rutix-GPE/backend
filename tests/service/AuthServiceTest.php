<?php

namespace App\Tests\Service;

use App\Service\AuthService;
use App\Entity\User;
use App\Dto\Auth\UserLoginDTO;
use App\Dto\Auth\UserRegisterDTO;
use App\Dto\User\UserResponseDTO;
use App\Repository\UserRepository;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use PHPUnit\Framework\TestCase;

class AuthServiceTest extends TestCase
{
    private $userRepository;
    private $passwordHasher;
    private $jwtManager;
    private $serializer;
    private $validator;
    private $service;
    private $violationList;

    protected function setUp(): void
    {
        $this->userRepository = $this->createMock(UserRepository::class);
        $this->passwordHasher = $this->createMock(UserPasswordHasherInterface::class);
        $this->jwtManager = $this->createMock(JWTTokenManagerInterface::class);
        $this->serializer = $this->createMock(SerializerInterface::class);
        $this->validator = $this->createMock(ValidatorInterface::class);

        // Default: always return a ConstraintViolationListInterface mock with 0 count
        $this->violationList = $this->createMock(ConstraintViolationListInterface::class);
        $this->violationList->method('count')->willReturn(0);

        $this->validator->method('validate')->willReturn($this->violationList);

        $this->service = new AuthService(
            $this->userRepository,
            $this->passwordHasher,
            $this->jwtManager,
            $this->serializer,
            $this->validator
        );
    }

    public function testRegisterCreatesUserAndReturnsDto()
    {
        $dto = new UserRegisterDTO();
        $dto->username = "john";
        $dto->firstname = "John";
        $dto->lastname = "Doe";
        $dto->email = "john@example.com";
        $dto->password = "pass";
        $dto->phonenumber = "0600000000";
        $dto->country = "FR";
        $dto->postalcode = "75000";
        $dto->city = "Paris";
        $dto->adress = "1 rue test";

        $requestMock = $this->createMock(Request::class);
        $requestMock->method('getContent')->willReturn(json_encode([
            'username' => "john",
            'firstname' => "John",
            'lastname' => "Doe",
            'email' => "john@example.com",
            'password' => "pass",
            'phonenumber' => "0600000000",
            'country' => "FR",
            'postalcode' => "75000",
            'city' => "Paris",
            'adress' => "1 rue test"
        ]));

        $this->serializer->method('deserialize')->willReturn($dto);
        $this->userRepository->method('findOneBy')->willReturn(null);
        $this->passwordHasher->method('hashPassword')->willReturn('hashed');

        // Correction ici : simulate l'ajout avec id
        $this->userRepository->expects($this->once())->method('add')
            ->willReturnCallback(function($user) {
                $user->id = 1;
            });

        $result = $this->service->controllerRegister($requestMock);

        $this->assertInstanceOf(UserResponseDTO::class, $result);
        $this->assertSame(1, $result->id);
    }


    public function testRegisterThrowsIfUserExists()
    {
        $dto = new UserRegisterDTO();
        $dto->username = "john";
        $dto->email = "john@example.com";

        $this->userRepository->method('findOneBy')->willReturn(new User());

        $this->expectException(BadRequestHttpException::class);
        $this->service->register($dto);
    }

    public function testControllerAuthenticateThrowsOnInvalidJson()
    {
        $requestMock = $this->createMock(Request::class);
        $requestMock->method('getContent')->willReturn('not a json');

        $this->expectException(BadRequestHttpException::class);
        $this->service->controllerAuthenticate($requestMock);
    }

    public function testAuthenticateThrowsOnWrongPassword()
    {
        $dto = $this->createMock(UserLoginDTO::class);
        $dto->method('isUsingEmail')->willReturn(true);
        $dto->method('getIdentifier')->willReturn('john@example.com');
        $dto->password = 'wrongpassword';

        $user = new User();
        $this->userRepository->method('findOneBy')->willReturn($user);
        $this->passwordHasher->method('isPasswordValid')->willReturn(false);

        $this->expectException(UnauthorizedHttpException::class);
        $this->service->authenticate($dto);
    }

    public function testAuthenticateReturnsUserOnSuccess()
    {
        $dto = $this->createMock(UserLoginDTO::class);
        $dto->method('isUsingEmail')->willReturn(true);
        $dto->method('getIdentifier')->willReturn('john@example.com');
        $dto->password = 'ok';

        $user = new User();
        $this->userRepository->method('findOneBy')->willReturn($user);
        $this->passwordHasher->method('isPasswordValid')->willReturn(true);

        $result = $this->service->authenticate($dto);
        $this->assertSame($user, $result);
    }
}
