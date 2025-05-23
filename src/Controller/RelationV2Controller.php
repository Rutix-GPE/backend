<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class RelationV2Controller extends AbstractController
{
    #[Route('/relation/v2', name: 'app_relation_v2')]
    public function index(): Response
    {
        return $this->render('relation_v2/index.html.twig', [
            'controller_name' => 'RelationV2Controller',
        ]);
    }
}
