<?php

namespace Admin\PathwayPlan;

use Admin\ModelAbstract\ServiceAbstract as ServiceAbstract;

use Admin\PathwayPlan\Entity as PathwayPlan;
use Admin\PathwayPlan\Table as PathwayPlanTable;
use Admin\Plan\Service as PlanService;
use Admin\Step\Service as StepService;

class Service extends ServiceAbstract
{

    protected $planService;
    protected $stepService;

    public function __construct(PathwayPlanTable $stepTable, PlanService $planService, StepService $stepService) {
        parent::__construct($stepTable);
        $this->planService= $planService;
        $this->stepService = $stepTable;
    }

    public function create($data) {

        $pathwayStep = new PathwayPlan();

        $pathwayStep->create($data);

        $pathwayStep = $this->table->save($pathwayStep);

        return $pathwayStep;

    }

}