<?php

namespace App\Controller;

use App\Entity\TemplateQuestion;
use App\Repository\TemplateQuestionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class TemplateQuestionController extends AbstractController
{

    #[Route('/template-question/new', name:'new_template_question', methods: ['POST'])]
    public function new(Request $request, TemplateQuestionRepository $TQRepository): JsonResponse
    {
        
        $data = $request->getContent();
        $data = json_decode($data, true);
        
        if(!isset($data['name']) || 
        !isset($data['content']) ||
        !isset($data['type'])) {
            $response = ["error" => "Missing informations"];
            return $this->json($response, Response::HTTP_BAD_REQUEST);
        }
        
        if(!in_array($data["type"], [TemplateQuestion::LABEL_TEXT, TemplateQuestion::LABEL_MULTIPLE_CHOICE])) {
            $response = ["error" => "Choose beetwen type 'text' or 'multiple_choice'"];
            return $this->json($response, Response::HTTP_BAD_REQUEST);
        }
        // return $this->json("", 200);

        try{
            $question = new TemplateQuestion;

            $question->setName($data["name"]);
            $question->setContent($data["content"]);            
            $question->setType($data["type"]);
            $question->setChoice($data["choice"]);

            if(isset($data['page'])){
                $question->setPage($data['page']);
            }

            $TQRepository->add($question, true);

            return $this->json($question, Response::HTTP_CREATED);

        } catch (\Exception $error) {
            $response = ["error" => $error->getMessage()];
            return $this->json($response, Response::HTTP_BAD_REQUEST);
        }
    }

    #[Route('/template-question/show/{id}', name:'show_template_question', methods: ['GET'])]
    public function show($id, TemplateQuestionRepository $TQRepository): JsonResponse
    {
        $question = $TQRepository->find($id);

        if(!$question){
            $response = ["msg" => "Not found"];
            return $this->json($response, Response::HTTP_NOT_FOUND);
        }

        return $this->json($question, Response::HTTP_OK);
    }

    #[Route('/template-question/list', name: 'list_template_question', methods: ['GET'])]
    public function listQT(TemplateQuestionRepository $TQRepository): JsonResponse
    {
        $questions = $TQRepository->findAll();

        if (!$questions) {
            $response = ["msg" => "Zero questions"];
            return $this->json($response, 404);
        }

        return $this->json($questions, Response::HTTP_OK);
    }

    #[Route('/template-question/list/page/{id}', name:'list_template_question_by_page', methods: ['GET'])]
    public function byPageQT($id, TemplateQuestionRepository $TQRepository): JsonResponse
    {
        $question = $TQRepository->findBy(['page' => $id]);

        if(!$question){
            $response = ["msg" => "Zero questions"];
            return $this->json($response, Response::HTTP_NOT_FOUND);
        }

        return $this->json($question, Response::HTTP_OK);
    }

    // #[Route('/template-question/update/{id}', name: 'update_list_template', methods: ['PUT'])]
    // public function updateQT(Request $request, $id, TemplateQuestionRepository $TQRepository): JsonResponse
    // {
    //     $question = $TQRepository->find($id);

    //     if(!$question){
    //         $response = ["msg" => "Not found"];
    //         return $this->json($response, Response::HTTP_NOT_FOUND);
    //     }

    //     $data = $request->getContent();
    //     $data = json_decode($data, true);

    //     try{
    //         if(isset($data["name"])){
    //             $question->setName($data["name"]);
    //         }
    //         if(isset($data["content"])){
    //             $question->setContent($data["content"]);
    //         }
    //         if(isset($data["type"])){
    //             $question->setType($data["type"]);
    //         }
    //         if(isset($data["choice"])){
    //             $question->setChoice($data['choice']);
    //         }

    //         $TQRepository->add($question, true);

    //         return $this->json($question, Response::HTTP_CREATED);

    //     }catch(\Exception $error){
    //         $response = ["error" => $error->getMessage()];
    //         return $this->json($response, Response::HTTP_BAD_REQUEST);
    //     }
    // }

    #[Route('/template-question/delete/{id}', name: 'delete_template-question', methods: ['DELETE'])]
    public function delete($id, Request $request, TemplateQuestionRepository $TQRepository): JsonResponse
    {
        $question = $TQRepository->find($id);

        if(!$question){
            $response = ["msg" => "Not found"];
            return $this->json($response, Response::HTTP_NOT_FOUND);
        }

        $TQRepository->remove($question, true);

        $question = $TQRepository->find($id);

        if($question){
            $response = ["success" => false];
            return $this->json($response, Response::HTTP_INTERNAL_SERVER_ERROR);
        } else {
            $response = ["success" => true];
            return $this->json($response, Response::HTTP_OK);
        }
    }
}
