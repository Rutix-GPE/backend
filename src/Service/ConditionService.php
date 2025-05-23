<?php 

namespace App\Service;

use App\Dto\ConditionRoutine\ConditionRoutineOutputDTO;
use App\Entity\User;
use App\Entity\Routine;
use App\Entity\ConditionRoutine;
use App\Repository\TaskRepository;
use App\Repository\RoutineRepository;
use App\Repository\ConditionRoutineRepository;
use App\Repository\TemplateQuestionRepository;
use App\Repository\UserRepository;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use App\Dto\ConditionRoutine\ConditionRoutineInputDTO;

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
        $errors = $this->validator->validate($inputDto);
        if (count($errors) > 0) {
            throw new BadRequestHttpException("Données invalides : " . (string) $errors);
        }
        return new ConditionRoutineOutputDTO($this->createRoutineConditon($inputDto));

       //  $category = $this->categoryRepository->find($inputDto->categoryId);
         /*if (!$category) {
             throw new NotFoundHttpException("Catégorie introuvable");
         }*/

     
 
        /*
        
        // TODO rajouter le setDays
        $condition = new ConditionRoutine;

        $condition->setName($data["name"]);
        $condition->setDescription($data["description"]); 
        
        $dateTime = \DateTime::createFromFormat('H:i:s', $data["time"]);
        $condition->setTaskTime($dateTime);

        $question = $this->templateQuestionRepository->find($data["question"]);
        $condition->setQuestion($question);
        $condition->setResponseCondition($data["response"]);

        if(isset($data['days'])){
            $condition->setDays($data['days']);
        }


        $this->conditionRepository->add($condition, true);


        return $condition;*/
    }
    function createRoutineConditon($inputDto){
         
        $question = $this->templateQuestionRepository->find($inputDto->questionId);
        if (!$question) {
            throw new NotFoundHttpException("Question introuvable");
        }
        $routine = new ConditionRoutine();
        $routine->setName($inputDto->name);
        $routine->setDescription($inputDto->description);
        $routine->setTaskTime(new \DateTime($inputDto->taskTime));
        $routine->setDays($inputDto->days);
        $routine->setResponseCondition($inputDto->responseCondition);
        $routine->setCreationDate(new \DateTime());
        $routine->setUpdatedDate(new \DateTime());
        $routine->setQuestion($question);
        return $routine;

    }

}
