<?php

namespace Admin\GradeLevel;

use Admin\ModelAbstract\EntityAbstract;

class Entity extends EntityAbstract {

    public $name;
    public $sortOrder;

    public function numericValue() {
        return preg_replace('/[a-z]+/', '', $this->name);
    }

    public function create($data) {

        parent::create($data);
        $this->name = (!empty($data['name'])) ? $data['name'] : null;
        $this->sortOrder = (!empty($data['sort_order'])) ? $data['sort_order'] : null;

    }

    public function exchangeArray(array $data)
    {

        if (!$this->id) $this->id = (isset($data['id']) && !empty($data['id'])) ? $data['id'] : null;

        $this->name = (!empty($data['name'])) ? $data['name'] : null;
        $this->sortOrder = (!empty($data['sort_order'])) ? $data['sort_order'] : null;

        $this->exchangeDates($data);
    }

    public function toData() {

        $data = array(
            'name' => $this->name,
            'sort_order' => $this->sortOrder,
        );

        return $data;

    }

    public function getArrayCopy() {
        return $this->toData();
    }

}