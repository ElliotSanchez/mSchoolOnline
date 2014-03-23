<?php

namespace Admin\Step;

use Admin\ModelAbstract\EntityAbstract;

class Entity extends EntityAbstract {

    public $stepOrder;
    public $timer;
    public $isActive;

    public $studentId;
    public $resourceId;

    public function create($data) {

        parent::create($data);
        $this->stepOrder = (!empty($data['step_order'])) ? $data['step_order'] : null;
        $this->timer = (!empty($data['timer'])) ? $data['timer'] : null;
        $this->isActive = (!empty($data['is_active'])) ? $data['is_active'] : null;

        $this->resourceId = (!empty($data['resource_id'])) ? $data['resource_id'] : null;

    }

    public function exchangeArray(array $data)
    {

        if (!$this->id) $this->id = (isset($data['id']) && !empty($data['id'])) ? $data['id'] : null;

        $this->stepOrder = (!empty($data['step_order'])) ? $data['step_order'] : null;
        $this->timer = (!empty($data['timer'])) ? $data['timer'] : null;
        $this->isActive = (!empty($data['is_active'])) ? $data['is_active'] : null;

        $this->resourceId = (!empty($data['resource_id'])) ? $data['resource_id'] : null;

        $this->exchangeDates($data);
    }

    public function toData() {

        $data = array(
            'resource_id' => $this->resourceId,
            'step_order' => $this->stepOrder,
            'timer' => $this->timer,
            'is_active' => $this->isActive,
        );

        return $data;

    }

    public function getArrayCopy() {
        return $this->toData();
    }

}