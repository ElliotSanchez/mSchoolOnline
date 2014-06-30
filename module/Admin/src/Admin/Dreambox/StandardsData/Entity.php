<?php

namespace Admin\Dreambox\StandardsData;

use Admin\ModelAbstract\EntityAbstract;

class Entity extends EntityAbstract {

    public $dreamboxStandardsId;
    public $column;
    public $value;

    public function create($data) {

        parent::create($data);

        $this->dreamboxStandardsId = (!empty($data['dreambox_standards_id'])) ? $data['dreambox_standards_id'] : null;
        $this->column = (!empty($data['column'])) ? $data['column'] : null;
        $this->value = (!empty($data['value'])) ? $data['value'] : null;

    }

    public function exchangeArray(array $data)
    {

        if (!$this->id) $this->id = (isset($data['id']) && !empty($data['id'])) ? $data['id'] : null;

        $this->dreamboxStandardsId = (!empty($data['dreambox_standards_id'])) ? $data['dreambox_standards_id'] : $this->dreamboxStandardsId;
        $this->column = (!empty($data['column'])) ? $data['column'] : $this->column;
        $this->value = (!empty($data['value'])) ? $data['value'] : $this->value;

        $this->exchangeDates($data);
    }

    public function toData() {

        $data = array(
            'dreambox_standards_id' => $this->dreamboxStandardsId,
            'column' => $this->column,
            'value' => $this->value,
        );

        return $data;

    }

    public function getArrayCopy() {
        return $this->toData();
    }

}