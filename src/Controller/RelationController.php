<?php

namespace App\Controller;

use App\Service\RelationService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

final class RelationController extends AbstractController
{
    #[Route('/relation/join', name: 'join_relation', methods: ['POST'])]
    public function join(Request $request, RelationService $relationService): JsonResponse
    {
        $user = $this->getUser();
        if (!$user) {
            throw new UnauthorizedHttpException('Bearer', 'Utilisateur non authentifié.');
        }
        if (!in_array('ROLE_ADMIN', $user->getRoles())) {
            throw new UnauthorizedHttpException('acces', "Accès refusé");
        }

        $data = $request->getContent();
        $data = json_decode($data, true);

        if(!isset($data['source']) || 
        !isset($data['target']) ||
        !isset($data['typeTarget']) ||
        !isset($data['answer'])) {
            $response = ["error" => "Missing informations"];
            return $this->json($response, Response::HTTP_BAD_REQUEST);
        }

        $source = $data['source'];
        $target = $data['target'];
        $typeTarget = $data['typeTarget'];
        $answer = $data['answer'];

        $relation = $relationService->join($source, $target, $typeTarget, $answer);


        return $this->json($relation);
    }

    #[Route('/relation/source-target/{question}', name: 'get_answer', methods: ['GET'])]
    public function sourceAndTarget($question, Request $request, RelationService $relationService): JsonResponse
    {
        $user = $this->getUser();
        if (!$user) {
            throw new UnauthorizedHttpException('Bearer', 'Utilisateur non authentifié.');
        }
        if (!in_array('ROLE_ADMIN', $user->getRoles())) {
            throw new UnauthorizedHttpException('acces', "Accès refusé");
        }

        if(!$question){
            $response = ["error" => "Missing informations"];
            return $this->json($response, Response::HTTP_BAD_REQUEST);
        }
        
        
        $target = $relationService->getTargetQuestion($question);
        $source = $relationService->getSource($question);

        $array = [
            "target" => $target,
            "source" => $source
        ];

        return $this->json([$array], Response::HTTP_OK);

    }    

        #[Route('/relation/answers/{question}', name: 'get_source_target', methods: ['GET'])]
    public function answer($question, Request $request, RelationService $relationService): JsonResponse
    {
        $user = $this->getUser();
        if (!$user) {
            throw new UnauthorizedHttpException('Bearer', 'Utilisateur non authentifié.');
        }
        if (!in_array('ROLE_ADMIN', $user->getRoles())) {
            throw new UnauthorizedHttpException('acces', "Accès refusé");
        }

        if(!$question){
            $response = ["error" => "Missing informations"];
            return $this->json($response, Response::HTTP_BAD_REQUEST);
        }
        
        
        $answers = $relationService->getAnswer($question);

        return $this->json($answers, Response::HTTP_OK);
    }  
}
