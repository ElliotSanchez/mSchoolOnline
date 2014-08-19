<?php

namespace Admin\Step;

use Admin\ModelAbstract\EntityAbstract;

class Entity extends EntityAbstract {

    public $name;
    public $shortCode;
    public $timer;
    public $showPopup;
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
        $this->shortCode = trim((!empty($data['short_code'])) ? $data['short_code'] : null);
        $this->timer = (!empty($data['timer'])) ? $data['timer'] : null;
        $this->showPopup = (!empty($data['show_popup'])) ? $data['show_popup'] : null;
        $this->isActive = (!empty($data['is_active'])) ? $data['is_active'] : null;

        $this->resourceId = (!empty($data['resource_id'])) ? $data['resource_id'] : null;

    }

    public function exchangeArray(array $data)
    {

        if (!$this->id) $this->id = (isset($data['id']) && !empty($data['id'])) ? $data['id'] : null;

        $this->name = (!empty($data['name'])) ? $data['name'] : null;
        $this->shortCode = trim((!empty($data['short_code'])) ? $data['short_code'] : null);
        $this->timer = (!empty($data['timer'])) ? $data['timer'] : null;
        $this->isActive = (!empty($data['is_active'])) ? $data['is_active'] : null;
        $this->showPopup = (!empty($data['show_popup'])) ? $data['show_popup'] : $this->showPopup;

        $this->resourceId = (!empty($data['resource_id'])) ? $data['resource_id'] : null;

        $this->exchangeDates($data);
    }

    public function toData() {

        $data = array(
            'resource_id' => $this->resourceId,
            'name' => $this->name,
            'short_code' => trim($this->shortCode),
            'timer' => $this->timer,
            'show_popup' => $this->showPopup,
            'is_active' => $this->isActive,
        );

        return $data;

    }

    public function getArrayCopy() {
        return $this->toData();
    }

}