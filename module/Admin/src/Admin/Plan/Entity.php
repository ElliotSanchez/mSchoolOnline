<?php

namespace Admin\Plan;

use Admin\ModelAbstract\EntityAbstract;

class Entity extends EntityAbstract {

    public $code;
    public $isActive;

    public function create($data) {

        parent::create($data);
        $this->code = (!empty($data['code'])) ? $data['code'] : null;
        $this->isActive = (!empty($data['is_active'])) ? $data['is_active'] : null;
    }

    public function exchangeArray(array $data)
    {
        if (!$this->id) $this->id = (isset($data['id']) && !empty($data['id'])) ? $data['id'] : null;

        $this->code = (!empty($data['code'])) ? $data['code'] : null;
        $this->isActive = (!empty($data['is_active'])) ? $data['is_active'] : null;

        $this->exchangeDates($data);
    }

    public function toData() {

        $data = array(
            'code' => $this->code,
            'is_active' => $this->isActive,
        );

        return $data;

    }

    public function getArrayCopy() {
        return $this->toData();
    }

}