<?php

namespace Admin\PlanStep;

use Admin\ModelAbstract\EntityAbstract;

class Entity extends EntityAbstract {

    public $planId;
    public $stepId;

    public $plan;
    public $step;

    public function create($data) {

        parent::create($data);
        $this->planId = (!empty($data['plan_id'])) ? $data['plan_id'] : null;
        $this->stepId = (!empty($data['step_id'])) ? $data['step_id'] : null;

    }

    public function exchangeArray(array $data)
    {

        if (!$this->id) $this->id = (isset($data['id']) && !empty($data['id'])) ? $data['id'] : null;

        $this->planId = (!empty($data['plan_id'])) ? $data['plan_id'] : null;
        $this->stepId = (!empty($data['step_id'])) ? $data['step_id'] : null;

        $this->exchangeDates($data);
    }

    public function toData() {

        $data = array(
            'plan_id' => $this->planId,
            'step_id' => $this->stepId,
        );

        return $data;

    }

    public function getArrayCopy() {
        return $this->toData();
    }

}