<?php

namespace Admin\ThinkThroughMath\Student;

use Admin\ModelAbstract\ServiceAbstract as ServiceAbstract;
use Admin\ThinkThroughMath\Student\Entity as ThinkThroughMathStudent;

class Service extends ServiceAbstract
{

    public function create($data) {

        $entity = new ThinkThroughMathStudent();

        $entity->create($data);

        $entity = $this->table->save($entity);

        return $entity;

    }
}