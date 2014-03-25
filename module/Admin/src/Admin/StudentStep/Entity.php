<?php

namespace Admin\StudentStep;

use Admin\ModelAbstract\EntityAbstract;

class Entity extends EntityAbstract {

    public $stepOrder;
    //public $timer;
    //public $isActive;

    public $studentId;
    public $pathwayId;
    public $planId;
    public $stepId;
    //public $resourceId;

    public function create($data) {

        parent::create($data);
        $this->stepOrder = (!empty($data['step_order'])) ? $data['step_order'] : null;
//        $this->timer = (!empty($data['timer'])) ? $data['timer'] : null;
//        $this->isActive = (!empty($data['is_active'])) ? $data['is_active'] : null;

        $this->studentId = (!empty($data['student_id'])) ? $data['student_id'] : null;
        $this->pathwayId = (!empty($data['pathway_id'])) ? $data['pathway_id'] : null;
        $this->planId = (!empty($data['plan_id'])) ? $data['plan_id'] : null;
        $this->stepId = (!empty($data['step_id'])) ? $data['step_id'] : null;

//        $this->resourceId = (!empty($data['resource_id'])) ? $data['resource_id'] : null;

    }

    public function exchangeArray(array $data)
    {

        if (!$this->id) $this->id = (isset($data['id']) && !empty($data['id'])) ? $data['id'] : null;

        $this->stepOrder = (!empty($data['step_order'])) ? $data['step_order'] : null;
//        $this->timer = (!empty($data['timer'])) ? $data['timer'] : null;
//        $this->isActive = (!empty($data['is_active'])) ? $data['is_active'] : null;

        $this->studentId = (!empty($data['student_id'])) ? $data['student_id'] : null;
        $this->studentId = (!empty($data['student_id'])) ? $data['student_id'] : null;
        $this->pathwayId = (!empty($data['pathway_id'])) ? $data['pathway_id'] : null;
        $this->planId = (!empty($data['plan_id'])) ? $data['plan_id'] : null;
        $this->stepId = (!empty($data['step_id'])) ? $data['step_id'] : null;

//        $this->resourceId = (!empty($data['resource_id'])) ? $data['resource_id'] : null;

        $this->exchangeDates($data);
    }

    public function toData() {

        $data = array(
            'student_id' => $this->studentId,
            'pathway_id' => $this->pathwayId,
            'plan_id' => $this->planId,
            'step_id' => $this->stepId,
            'step_order' => $this->stepOrder,
//            'timer' => $this->timer,
//            'is_active' => $this->isActive,
//            'resource_id' => $this->resourceId,
        );

        return $data;

    }

    public function getArrayCopy() {
        return $this->toData();
    }

}