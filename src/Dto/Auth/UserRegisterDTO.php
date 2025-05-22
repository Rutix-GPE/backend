<?php

namespace App\Dto\Auth;

use Symfony\Component\Validator\Constraints as Assert;

class UserRegisterDTO
{
    #[Assert\NotBlank]
    public string $username;

    #[Assert\NotBlank]
    public string $firstname;

    #[Assert\NotBlank]
    public string $lastname;
    #[Assert\NotBlank]
    #[Assert\Email]
    public string $email;
    #[Assert\NotBlank]

    public string $password;
    public ?string $phonenumber = null;
    public ?string $country = null;
    public ?string $postalcode = null;
    public ?string $city = null;
    public ?string $adress = null;
    public ?string $memo = null;



}
