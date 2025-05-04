<?php 

namespace App\Service;

use App\Entity\ConditionRoutine;
use App\Entity\Routine;
use App\Entity\User;
use App\Repository\ConditionRoutineRepository;
use App\Repository\RoutineRepository;
use App\Repository\TaskRepository;
use App\Repository\TemplateQuestionRepository;

class ConditionService 
{

    private ConditionRoutineRepository $conditionRepository;
    private TemplateQuestionRepository $templateQuestionRepository;

    private RoutineService $routineService;

    
    public function __construct(
        ConditionRoutineRepository $conditionRepository, 
        TemplateQuestionRepository $templateQuestionRepository,
        RoutineService $routineService
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

    public function controllerCreateCondition($data)
    {
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


        return $condition;
    }

}
