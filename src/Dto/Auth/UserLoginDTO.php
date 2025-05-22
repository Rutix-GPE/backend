<?php 
namespace App\Dto\Auth;

use Symfony\Component\Serializer\Annotation\SerializedName;
use Symfony\Component\Serializer\Attribute\MapFrom;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

final readonly class UserLoginDTO
{
    #[Assert\Email(message: "Format d'email invalide")]
    public ?string $email;

    #[Assert\Regex(
        pattern: '/^[a-zA-Z0-9]+$/',
        message: "Le nom d'utilisateur doit être alphanumérique"
    )]
    public ?string $username;

    #[Assert\NotBlank(message: "Le mot de passe est requis.")]
    public string $password;

    public function __construct(
        ?string $email = null,
        ?string $username = null,
        string $password = ''
    ) {
        $this->email = $email;
        $this->username = $username;
        $this->password = $password;
    }


    public function getIdentifier(): string
    {
        return $this->email ?? $this->username;
    }
    public function isUsingEmail():bool{
        return $this->email !== null;
    }

}
