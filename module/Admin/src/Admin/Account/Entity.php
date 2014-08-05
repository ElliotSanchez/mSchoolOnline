<?php

namespace Admin\Account;

use Admin\ModelAbstract\EntityAbstract;

class Entity extends EntityAbstract {

    public $name;
    public $subdomain;
    public $isDefault;

    public function create($data) {

        parent::create($data);
        $this->name = (!empty($data['name'])) ? $data['name'] : null;
        $this->subdomain = (!empty($data['subdomain'])) ? $data['subdomain'] : null;
        $this->isDefault = (!empty($data['is_default'])) ? $data['is_default'] : 0;

    }

    public function exchangeArray(array $data)
    {

        if (!$this->id) $this->id = (isset($data['id']) && !empty($data['id'])) ? $data['id'] : null;

        $this->name = (!empty($data['name'])) ? $data['name'] : null;
        $this->subdomain = (!empty($data['subdomain'])) ? $data['subdomain'] : null;
        $this->isDefault = (int) ((!empty($data['is_default'])) ? $data['is_default'] : $this->isDefault);

        $this->exchangeDates($data);
    }

    public function toData() {

        $data = array(
            'name' => $this->name,
            'subdomain' => $this->subdomain,
            'is_default' => (int) $this->isDefault,
        );

        return $data;

    }

    public function getArrayCopy() {
        return $this->toData();
    }

}