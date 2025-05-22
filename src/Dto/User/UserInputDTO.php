<?php
// src/Dto/UserUpdateDTO.php
namespace App\Dto\User;

use Symfony\Component\Validator\Constraints as Assert;

class UserInputDTO
{
    public ?string $username = null;
    #[Assert\Email]
    public ?string $email = null;

    public ?string $firstname = null;
    public ?string $lastname = null;
    public ?string $password = null;
    public ?string $phonenumber = null;
    public ?string $country = null;
    public ?string $postalcode = null;
    public ?string $city = null;
    public ?string $adress = null;
    public ?string $memo = null;
    public ?string $avatarFile = null;
}
