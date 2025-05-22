<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class AvatarController extends AbstractController
{
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
            array_push($imageUrls,  $key);
        }

        return $this->json($imageUrls);
    }

    #[Route('/avatar/get/{filename}', name: 'get_avatar', methods: ['GET'])]
    public function getAvatar(string $filename): Response
    {
        if (!preg_match('/^[a-zA-Z0-9_\-]+\.(jpg|jpeg|png)$/i', $filename)) {
            return new Response('Invalid filename.', 400);
        }

        $imagePath = $this->getParameter('kernel.project_dir') . '/public/img/avatar/' . $filename;

        if (!file_exists($imagePath)) {
            return new Response('File not found.', 404);
        }

        $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        $mimeTypes = [
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'png' => 'image/png',
        ];
        $mimeType = $mimeTypes[$extension] ?? 'application/octet-stream';

        $content = file_get_contents($imagePath);
        $response = new Response($content);
        $response->headers->set('Content-Type', $mimeType);

        return $response;
    }

}
