<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class AvatarController extends AbstractController
{
    #[Route('/avatar', name: 'app_avatar')]
    public function index(): Response
    {
        return $this->render('avatar/index.html.twig', [
            'controller_name' => 'AvatarController',
        ]);
    }

    #[Route('/avatar/list', name: 'list_avatar', methods: ['GET'])]
    public function listAvatar()
    {
        $imageDir = $this->getParameter('kernel.project_dir') . '/public/img/avatar';
        $files = scandir($imageDir);
        $images = array_filter($files, function ($file) use ($imageDir) {
            return is_file($imageDir . '/' . $file) && preg_match('/\.(jpg|jpeg|png)$/i', $file);
        });

        $imageUrls = [];
        foreach ($images as $key ) {
            $imageUrls[$key] = 'https://localhost:8090/img/avatar/' . $key;
        }

        return $this->json($imageUrls);
    }
}
