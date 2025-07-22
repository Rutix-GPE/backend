<?php
namespace App\Dto\Category;

use Symfony\Component\Validator\Constraints as Assert;

class CategoryDTO
{
    #[Assert\NotBlank()]
    public string $name;


  
}
