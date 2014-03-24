<?php

namespace Admin\PathwayPlan;

use Admin\ModelAbstract\ServiceAbstract as ServiceAbstract;

use Admin\PathwayPlan\Entity as PathwayPlan;
use Admin\PathwayPlan\Table as PathwayPlanTable;
use Admin\Plan\Service as PlanService;
use Admin\Pathway\Service as PathwayService;
use Admin\Pathway\Entity as Pathway;

class Service extends ServiceAbstract
{

    protected $pathwayService;
    protected $planService;

    public function __construct(PathwayPlanTable $stepTable, PathwayService $pathwayService, PlanService $planService) {
        parent::__construct($stepTable);
        $this->pathwayService = $pathwayService;
        $this->planService= $planService;
    }

    public function create($data) {

        $pathwayStep = new PathwayPlan();

        $pathwayStep->create($data);

        $pathwayStep = $this->table->save($pathwayStep);

        return $pathwayStep;

    }

    public function plansForPathway(Pathway $pathway) {

        $pathwayPlans = $this->table->fetchWith(array('pathway_id' => $pathway->id));

        $ids = array();

        foreach ($pathwayPlans as $pathwayPlan) {
            $ids[] = $pathwayPlan->id;
        }

        return $this->planService->get($ids);

    }

}