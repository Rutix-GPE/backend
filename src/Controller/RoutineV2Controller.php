<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class RoutineV2Controller extends AbstractController
{
    #[Route('/routine/v2', name: 'app_routine_v2')]
    public function index(): Response
    {
        return $this->render('routine_v2/index.html.twig', [
            'controller_name' => 'RoutineV2Controller',
        ]);
    }
}
