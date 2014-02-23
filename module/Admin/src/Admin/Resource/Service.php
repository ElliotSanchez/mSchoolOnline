<?php

namespace Admin\Resource;

use Admin\ModelAbstract\ServiceAbstract as ServiceAbstract;
use Admin\Resource\Entity as Resource;

class Service extends ServiceAbstract {

    public function create($data) {

        $resource = new Resource();

        $resource->create($data);

        $resource = $this->table->save($resource);

        return $resource;

    }

    public function getWithShortCode($code) {

        return $this->table->fetchWith(array('short_code' => $code))->current();

    }

}