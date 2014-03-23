<?php

namespace Admin\StudentLogin;

use Admin\ModelAbstract\EntityAbstract;

class Entity extends EntityAbstract {

    public $loginAt;

    public $studentId;

    public function create($data) {

        parent::create($data);
        $this->loginAt = (!empty($data['login_at'])) ? (new \DateTime($data['login_at'])) : null;

        $this->studentId = (!empty($data['student_id'])) ? $data['student_id'] : null;

    }

    public function exchangeArray(array $data)
    {

        if (!$this->id) $this->id = (isset($data['id']) && !empty($data['id'])) ? $data['id'] : null;
        $this->loginAt = (!empty($data['login_at'])) ? (new \DateTime($data['login_at'])) : null;

        $this->studentId = (!empty($data['student_id'])) ? $data['student_id'] : null;

        $this->exchangeDates($data);
    }

    public function toData() {

        $data = array(
            'login_at' => ($this->loginAt) ? ($this->loginAt->format('Y-m-d H:i:s')) : (null),
            'student_id' => $this->studentId,
        );

        return $data;

    }

    public function getArrayCopy() {
        return $this->toData();
    }

}