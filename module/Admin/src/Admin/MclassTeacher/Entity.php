<?php

namespace Admin\MclassTeacher;

use Admin\ModelAbstract\EntityAbstract;

class Entity extends EntityAbstract {

    public $mclassId;
    public $teacherId;

    public $mclass;
    public $teacher;

    public function create($data) {

        parent::create($data);
        $this->mclassId = (!empty($data['mclass_id'])) ? $data['mclass_id'] : null;
        $this->teacherId = (!empty($data['teacher_id'])) ? $data['teacher_id'] : null;

    }

    public function exchangeArray(array $data)
    {

        if (!$this->id) $this->id = (isset($data['id']) && !empty($data['id'])) ? $data['id'] : null;

        $this->mclassId = (!empty($data['mclass_id'])) ? $data['mclass_id'] : null;
        $this->teacherId = (!empty($data['teacher_id'])) ? $data['teacher_id'] : null;

        $this->exchangeDates($data);
    }

    public function toData() {

        $data = array(
            'mclass_id' => $this->mclassId,
            'teacher_id' => $this->teacherId,
        );

        return $data;

    }

    public function getArrayCopy() {
        return $this->toData();
    }

}