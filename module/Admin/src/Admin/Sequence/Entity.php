<?php

namespace Admin\Sequence;

use Admin\ModelAbstract\EntityAbstract;
use Admin\User\UserAbstract as UserAbstract;

class Entity extends UserAbstract {

    public $planGroups;
    public $isDefault;
    public $isComplete;

    public $studentId;

    public function create($data) {

        parent::create($data);
        $this->planGroups = (bool) (!empty($data['plan_groups'])) ? $data['plan_groups'] : null;
        $this->isDefault = (bool) (!empty($data['is_default'])) ? $data['is_default'] : false;
        $this->isComplete = (bool) (!empty($data['is_complete'])) ? $data['is_complete'] : false;
        $this->studentId = (!empty($data['student_id'])) ? $data['student_id'] : null;

    }

    public function exchangeArray(array $data)
    {

        if (!$this->id) $this->id = (isset($data['id']) && !empty($data['id'])) ? $data['id'] : null;

        $this->planGroups = (bool) (!empty($data['plan_groups'])) ? $data['plan_groups'] : null;
        $this->isDefault = (bool) (!empty($data['is_default'])) ? $data['is_default'] : false;
        $this->isComplete = (bool) (!empty($data['is_complete'])) ? $data['is_complete'] : false;

        $this->studentId = (!empty($data['student_id'])) ? $data['student_id'] : null;

        $this->exchangeDates($data);
    }

    public function toData() {

        $data = array(
            'plan_groups' => (int) $this->planGroups,
            'is_default' => (int) $this->isDefault,
            'is_complete' => (int) $this->isComplete,
            'student_id' => $this->studentId,
        );

        return $data;

    }

    public function getArrayCopy() {
        return $this->toData();
    }

}