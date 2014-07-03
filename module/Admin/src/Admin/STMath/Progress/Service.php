<?php

namespace Admin\STMath\Progress;

use Admin\ModelAbstract\ServiceAbstract as ServiceAbstract;
use Admin\STMath\Progress\Entity as STMathProgress;

class Service extends ServiceAbstract
{

    public function create($data) {

        $entity = new STMathProgress();

        $entity->create($data);

        $entity = $this->table->save($entity);

        return $entity;

    }

}