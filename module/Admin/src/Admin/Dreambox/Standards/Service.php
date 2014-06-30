<?php

namespace Admin\Dreambox\Standards;

use Admin\ModelAbstract\ServiceAbstract as ServiceAbstract;
use Admin\Dreambox\Standards\Entity as DreamboxStandards;

class Service extends ServiceAbstract
{

    public function create($data) {

        $entity = new DreamboxStandards();

        $entity->create($data);

        $entity = $this->table->save($entity);

        return $entity;

    }
}