<?php

namespace Admin\ThinkThroughMath\Overview;

use Admin\ModelAbstract\ServiceAbstract as ServiceAbstract;
use Admin\ThinkThroughMath\Overview\Entity as ThinkThroughMathOverview;

class Service extends ServiceAbstract
{

    public function create($data) {

        $entity = new ThinkThroughMathOverview();

        $entity->create($data);

        $entity = $this->table->save($entity);

        return $entity;

    }
}