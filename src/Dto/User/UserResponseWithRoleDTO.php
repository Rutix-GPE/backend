<?php

namespace App\Dto\User;

use App\Entity\User;

class UserResponseWithRoleDTO
{
    public string $username;
    public string $firstname;
    public string $lastname;
    public string $email;
    public ?string $phonenumber;
    public ?string $country;
    public ?string $postalcode;
    public ?string $city;
    public ?string $adress;
    public ?string $memo;

    /** @var string[] */
    public array $roles; 

    public function __construct(User $user)
    {
        $this->username = $user->getUsername();
        $this->firstname = $user->getFirstname();
        $this->lastname = $user->getLastname();
        $this->email = $user->getEmail();
        $this->phonenumber = $user->getPhonenumber();
        $this->country = $user->getCountry();
        $this->postalcode = $user->getPostalcode();
        $this->city = $user->getCity();
        $this->adress = $user->getAdress();
        $this->memo = $user->getMemo();

        $this->roles = $user->getRoles();
    }
}
