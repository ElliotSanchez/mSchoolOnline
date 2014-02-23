<?php

namespace Admin\Resource;

use Admin\ModelAbstract\EntityAbstract;

class Entity extends EntityAbstract {

    public $name;
    public $shortCode;
    public $url;
    public $isExternal;

    public function create($data) {

        parent::create($data);
        $this->name = (!empty($data['name'])) ? $data['name'] : null;
        $this->shortCode = (!empty($data['short_code'])) ? $data['short_code'] : null;
        $this->url = (!empty($data['url'])) ? $data['url'] : null;
        $this->isExternal = (!empty($data['is_external'])) ? $data['is_external'] : 0;

    }

    public function exchangeArray(array $data)
    {

        if (!$this->id) $this->id = (isset($data['id']) && !empty($data['id'])) ? $data['id'] : null;

        $this->name = (!empty($data['name'])) ? $data['name'] : null;
        $this->shortCode = (!empty($data['short_code'])) ? $data['short_code'] : null;
        $this->url = (!empty($data['url'])) ? $data['url'] : null;
        $this->isExternal = (!empty($data['is_external'])) ? $data['is_external'] : 0;

        $this->exchangeDates($data);
    }

    public function toData() {

        $data = array(
            'name' => $this->name,
            'short_code' => $this->shortCode,
            'url' => $this->url,
            'is_external' => $this->isExternal,
        );

        return $data;

    }

    public function getArrayCopy() {
        return $this->toData();
    }

}