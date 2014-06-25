<?php

namespace Admin\IreadyData;

use Admin\ModelAbstract\EntityAbstract;

class Entity extends EntityAbstract {

    public $ireadyId;
    public $column;
    public $value;

    public function create($data) {

        parent::create($data);

        $this->ireadyId = (!empty($data['iready_id'])) ? $data['iready_id'] : null;
        $this->column = (!empty($data['column'])) ? $data['column'] : null;
        $this->value = (!empty($data['value'])) ? $data['value'] : null;

    }

    public function exchangeArray(array $data)
    {

        if (!$this->id) $this->id = (isset($data['id']) && !empty($data['id'])) ? $data['id'] : null;

        $this->ireadyId = (!empty($data['iready_id'])) ? $data['iready_id'] : $this->ireadyId;
        $this->column = (!empty($data['column'])) ? $data['column'] : $this->column;
        $this->value = (!empty($data['value'])) ? $data['value'] : $this->value;

        $this->exchangeDates($data);
    }

    public function toData() {

        $data = array(
            'iready_id' => $this->ireadyId,
            'column' => $this->column,
            'value' => $this->value,
        );

        return $data;

    }

    public function getArrayCopy() {
        return $this->toData();
    }

}