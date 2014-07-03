<?php

namespace Admin\STMath\Usage;

use Admin\ModelAbstract\ServiceAbstract as ServiceAbstract;
use Admin\STMath\Usage\Entity as STMathUsage;

class Service extends ServiceAbstract
{

    public function create($data) {

        $entity = new STMathUsage();

        $entity->create($data);

        $entity = $this->table->save($entity);

        return $entity;

    }

}