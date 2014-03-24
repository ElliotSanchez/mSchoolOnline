<?php

namespace Admin\PathwayPlan;

use Admin\ModelAbstract\EntityAbstract;

class Entity extends EntityAbstract {

    public $pathwayId;
    public $planId;

    public $pathway;
    public $plan;

    public function create($data) {

        parent::create($data);
        $this->pathwayId = (!empty($data['pathway_id'])) ? $data['pathway_id'] : null;
        $this->planId = (!empty($data['plan_id'])) ? $data['plan_id'] : null;

    }

    public function exchangeArray(array $data)
    {

        if (!$this->id) $this->id = (isset($data['id']) && !empty($data['id'])) ? $data['id'] : null;

        $this->pathwayId = (!empty($data['pathway_id'])) ? $data['pathway_id'] : null;
        $this->planId = (!empty($data['plan_id'])) ? $data['plan_id'] : null;

        $this->exchangeDates($data);
    }

    public function toData() {

        $data = array(
            'pathway_id' => $this->pathwayId,
            'plan_id' => $this->planId,
        );

        return $data;

    }

    public function getArrayCopy() {
        return $this->toData();
    }

}