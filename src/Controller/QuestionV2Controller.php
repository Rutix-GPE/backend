<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class QuestionV2Controller extends AbstractController
{
    #[Route('/question/v2', name: 'app_question_v2')]
    public function index(): Response
    {
        return $this->render('question_v2/index.html.twig', [
            'controller_name' => 'QuestionV2Controller',
        ]);
    }
}
