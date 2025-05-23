<?php 

namespace App\Service;

use phpDocumentor\Reflection\Types\Boolean;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AvatarService extends WebTestCase
{

    
    public function checkExistAvatarFile($fileName, $projectDir)
    {
        $imagePath = $projectDir .= "/public/img/avatar/" . $fileName;
        
        return file_exists($imagePath) && is_file($imagePath);
    }
}
