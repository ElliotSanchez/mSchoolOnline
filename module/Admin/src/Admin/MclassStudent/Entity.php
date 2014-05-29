<?php

namespace Admin\MclassStudent;

use Admin\ModelAbstract\EntityAbstract;

class Entity extends EntityAbstract {

    public $mclassId;
    public $studentId;

    public $mclass;
    public $student;

    public function create($data) {

        parent::create($data);
        $this->mclassId = (!empty($data['mclass_id'])) ? $data['mclass_id'] : null;
        $this->studentId = (!empty($data['student_id'])) ? $data['student_id'] : null;

    }

    public function exchangeArray(array $data)
    {

        if (!$this->id) $this->id = (isset($data['id']) && !empty($data['id'])) ? $data['id'] : null;

        $this->mclassId = (!empty($data['mclass_id'])) ? $data['mclass_id'] : null;
        $this->studentId = (!empty($data['student_id'])) ? $data['student_id'] : null;

        $this->exchangeDates($data);
    }

    public function toData() {

        $data = array(
            'mclass_id' => $this->mclassId,
            'student_id' => $this->studentId,
        );

        return $data;

    }

    public function getArrayCopy() {
        return $this->toData();
    }

}