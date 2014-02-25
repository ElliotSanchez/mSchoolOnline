<?php

namespace Admin\Mclass;

use Admin\User\UserAbstract as UserAbstract;

class Entity extends UserAbstract {

    public $name;

    public $accountId;
    public $schoolId;

    public function create($data) {

        parent::create($data);
        $this->name = (!empty($data['name'])) ? $data['name'] : null;
        $this->accountId = (!empty($data['account_id'])) ? $data['account_id'] : null;
        $this->schoolId = (!empty($data['school_id'])) ? $data['school_id'] : null;

    }

    public function exchangeArray(array $data)
    {

        if (!$this->id) $this->id = (isset($data['id']) && !empty($data['id'])) ? $data['id'] : null;
        $this->name = (!empty($data['name'])) ? $data['name'] : null;
        $this->accountId = (!empty($data['account_id'])) ? $data['account_id'] : null;
        $this->schoolId = (!empty($data['school_id'])) ? $data['school_id'] : null;

        $this->exchangeDates($data);
    }

    public function toData() {

        $data = array(
            'name' => $this->name,
            'account_id' => $this->accountId,
            'school_id' => $this->schoolId,
        );

        return $data;

    }

    public function getArrayCopy() {
        return $this->toData();
    }

}