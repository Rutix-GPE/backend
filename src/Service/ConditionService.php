<?php 

namespace App\Service;

use App\Entity\User;
use App\Entity\ConditionRoutine;
use App\Dto\ConditionRoutine\ConditionRoutineInputDTO;
use App\Repository\ConditionRoutineRepository;
use App\Repository\TemplateQuestionRepository;
use Symfony\Component\Serializer\SerializerInterface;
use App\Dto\ConditionRoutine\ConditionRoutineOutputDTO;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class ConditionService 
{

    private ConditionRoutineRepository $conditionRepository;
    private TemplateQuestionRepository $templateQuestionRepository;
    private RoutineService $routineService;
    public function __construct(
        ConditionRoutineRepository $conditionRepository, 
        TemplateQuestionRepository $templateQuestionRepository,
        RoutineService $routineService,
        private readonly SerializerInterface $serializer,
        private readonly ValidatorInterface $validator
        ) 
    {
        $this->conditionRepository = $conditionRepository;
        $this->templateQuestionRepository = $templateQuestionRepository; // template
        $this->routineService = $routineService;
    }

    public function createRoutineByCondition($question, $response, User $user)
    {
        $condition = $this->conditionRepository->findOneBy(['Question' => $question, 'responseCondition' => $response]);

        if($condition) {

            $this->routineService->createRoutine($condition, $user);

        }
    }

   public function controllerCreateCondition($request)
    {
        $inputDto = $this->serializer->deserialize($request->getContent(),ConditionRoutineInputDTO::class,'json');
     //  dd($inputDto);
        $errors = $this->validator->validate($inputDto);
       // dd($errors);
        if (count($errors) > 0) {
            throw new BadRequestHttpException("Données invalides : " . (string) $errors);
        }
        return( new ConditionRoutineOutputDTO(conditionRoutine: $this->createRoutineConditon($inputDto)));
        
    
    }
    function createRoutineConditon($inputDto){
         
        $question = $this->templateQuestionRepository->find($inputDto->question);
        if (!$question) {
            throw new NotFoundHttpException("Question introuvable");
        }
        $routine = new ConditionRoutine();
        $routine->setName($inputDto->name);
        $routine->setDescription($inputDto->description);
        $routine->setTaskTime($inputDto->time);
        $routine->setResponseCondition($inputDto->response);
        $routine->setQuestion($question);
        $this->conditionRepository->add($routine, true);

        return $routine;

    }

}
