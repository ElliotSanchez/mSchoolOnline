<?php

namespace Admin\Dreambox\StandardsData;

use Admin\ModelAbstract\ServiceAbstract as ServiceAbstract;
use Admin\Dreambox\StandardsData\Entity as DreamboxStandardsData;

class Service extends ServiceAbstract
{

    public function create($data) {

        $entity = new DreamboxStandardsData();

        $entity->create($data);

        $entity = $this->table->save($entity);

        return $entity;

    }
}