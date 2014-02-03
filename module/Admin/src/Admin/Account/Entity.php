<?php

namespace Admin\Account;

use Admin\ModelAbstract\EntityAbstract;

class Entity extends EntityAbstract {

    public $id;
    public $name;
    public $subdomain;
    public $createdAt;
    public $updatedAt;

    public function create($data) {

        parent::create($data);
        $this->name = (!empty($data['name'])) ? $data['name'] : null;
        $this->subdomain = (!empty($data['subdomain'])) ? $data['subdomain'] : null;

    }

    public function exchangeArray(array $data)
    {

        if (!$this->id) $this->id = (isset($data['id']) && !empty($data['id'])) ? $data['id'] : null;

        $this->name = (!empty($data['name'])) ? $data['name'] : null;
        $this->subdomain = (!empty($data['subdomain'])) ? $data['subdomain'] : null;

        $this->exchangeDates($data);
    }

    public function toData() {

        $data = array(
            'name' => $this->name,
            'subdomain' => $this->subdomain,
        );

        return $data;

    }

    public function getArrayCopy() {
        return $this->toData();
    }

}