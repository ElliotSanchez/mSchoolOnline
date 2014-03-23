<?php

namespace Admin\Plan;

use Admin\ModelAbstract\EntityAbstract;

class Entity extends EntityAbstract {

    public $name;
    public $shortCode;
    public $isActive;

    public function create($data) {

        parent::create($data);
        $this->name = (!empty($data['name'])) ? $data['name'] : null;
        $this->shortCode = (!empty($data['short_code'])) ? $data['short_code'] : null;
        $this->isActive = (!empty($data['is_active'])) ? $data['is_active'] : null;
    }

    public function exchangeArray(array $data)
    {
        if (!$this->id) $this->id = (isset($data['id']) && !empty($data['id'])) ? $data['id'] : null;

        $this->name = (!empty($data['name'])) ? $data['name'] : null;
        $this->shortCode = (!empty($data['short_code'])) ? $data['short_code'] : null;
        $this->isActive = (!empty($data['is_active'])) ? $data['is_active'] : null;

        $this->exchangeDates($data);
    }

    public function toData() {

        $data = array(
            'name' => $this->name,
            'short_code' => $this->shortCode,
            'is_active' => $this->isActive,
        );

        return $data;

    }

    public function getArrayCopy() {
        return $this->toData();
    }

}