<?php

namespace Admin\Resource;

use Admin\ModelAbstract\EntityAbstract;

class Entity extends EntityAbstract {

    public $name;
    public $shortCode;
    public $url;

    public function create($data) {

        parent::create($data);
        $this->name = (!empty($data['name'])) ? $data['name'] : null;
        $this->shortCode = (!empty($data['short_code'])) ? $data['short_code'] : null;
        $this->url = (!empty($data['url'])) ? $data['url'] : null;

    }

    public function exchangeArray(array $data)
    {

        if (!$this->id) $this->id = (isset($data['id']) && !empty($data['id'])) ? $data['id'] : null;

        $this->name = (!empty($data['name'])) ? $data['name'] : null;
        $this->shortCode = (!empty($data['short_code'])) ? $data['short_code'] : null;
        $this->url = (!empty($data['url'])) ? $data['url'] : null;

        $this->exchangeDates($data);
    }

    public function toData() {

        $data = array(
            'name' => $this->name,
            'short_code' => $this->shortCode,
            'url' => $this->url,
        );

        return $data;

    }

    public function getArrayCopy() {
        return $this->toData();
    }

}