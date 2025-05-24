<?php

namespace App\Controller;

use App\Service\RelationV2Service;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class RelationV2Controller extends AbstractController
{
    #[Route('/relation/v2/join', name: 'join_relation', methods: ['POST'])]
    public function join(Request $request, RelationV2Service $relationService): JsonResponse
    {
        // $user = $this->getUser();
        // if (!$user) {
        //     throw new UnauthorizedHttpException('Bearer', 'Utilisateur non authentifié.');
        // }
        // if (!in_array('ROLE_ADMIN', $user->getRoles())) {
        //     throw new UnauthorizedHttpException('acces', "Accès refusé");
        // }

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

    #[Route('/relation/v2/source-target/{question}', name: 'get_source_target', methods: ['GET'])]
    public function sourceAndTarget($question, Request $request, RelationV2Service $relationService): JsonResponse
    {
        // $user = $this->getUser();
        // if (!$user) {
        //     throw new UnauthorizedHttpException('Bearer', 'Utilisateur non authentifié.');
        // }
        // if (!in_array('ROLE_ADMIN', $user->getRoles())) {
        //     throw new UnauthorizedHttpException('acces', "Accès refusé");
        // }

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
}
