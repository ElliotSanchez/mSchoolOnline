<?php

namespace Admin\Step;

use Admin\ModelAbstract\EntityAbstract;

class Entity extends EntityAbstract {

    public $name;
    public $shortCode;
    public $timer;
    public $isActive;

    public $studentId;
    public $resourceId;
    public $resource;

    public function isTimed() {
        return is_numeric($this->timer);
    }

    public function create($data) {

        parent::create($data);
        $this->name = (!empty($data['name'])) ? $data['name'] : null;
        $this->shortCode = (!empty($data['short_code'])) ? $data['short_code'] : null;
        $this->timer = (!empty($data['timer'])) ? $data['timer'] : null;
        $this->isActive = (!empty($data['is_active'])) ? $data['is_active'] : null;

        $this->resourceId = (!empty($data['resource_id'])) ? $data['resource_id'] : null;

    }

    public function exchangeArray(array $data)
    {

        if (!$this->id) $this->id = (isset($data['id']) && !empty($data['id'])) ? $data['id'] : null;

        $this->name = (!empty($data['name'])) ? $data['name'] : null;
        $this->shortCode = (!empty($data['short_code'])) ? $data['short_code'] : null;
        $this->timer = (!empty($data['timer'])) ? $data['timer'] : null;
        $this->isActive = (!empty($data['is_active'])) ? $data['is_active'] : null;

        $this->resourceId = (!empty($data['resource_id'])) ? $data['resource_id'] : null;

        $this->exchangeDates($data);
    }

    public function toData() {

        $data = array(
            'resource_id' => $this->resourceId,
            'name' => $this->name,
            'short_code' => $this->shortCode,
            'timer' => $this->timer,
            'is_active' => $this->isActive,
        );

        return $data;

    }

    public function getArrayCopy() {
        return $this->toData();
    }

}