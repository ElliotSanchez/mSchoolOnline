<?php

namespace Admin\PlanStep;

use Admin\ModelAbstract\ServiceAbstract as ServiceAbstract;

use Admin\PlanStep\Entity as PlanStep;
use Admin\PlanStep\Table as PlanStepTable;
use Admin\Plan\Entity as Plan;
use Admin\Plan\Service as PlanService;
use Admin\Step\Service as StepService;

class Service extends ServiceAbstract
{

    protected $planService;
    protected $stepService;

    public function __construct(PlanStepTable $stepTable, PlanService $planService, StepService $stepService) {
        parent::__construct($stepTable);
        $this->planService= $planService;
        $this->stepService = $stepService;
    }

    public function create($data) {

        $planStep = new PlanStep();

        $planStep->create($data);

        $planStep = $this->table->save($planStep);

        return $planStep;

    }

    public function getPlanSteps(Plan $plan) {

        $planSteps = iterator_to_array($this->table->fetchWith(array('plan_id' => $plan->id)));

        foreach ($planSteps as &$planStep) {
            $planStep->step = $this->stepService->get($planStep->stepId);
        }

        return $planSteps;

    }

    public function removePlanStep(PlanStep $planStep) {
        return $this->table->delete($planStep->id);
    }

}