<?php 

namespace App\Service;

use App\Entity\Category;
use App\Repository\CategoryRepository;

class CategoryService 
{

    private CategoryRepository $categoryRepository;
    
    public function __construct(CategoryRepository $categoryRepository) 
    {
        $this->categoryRepository = $categoryRepository;
    }

}
