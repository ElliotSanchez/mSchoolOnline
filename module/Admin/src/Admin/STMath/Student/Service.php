<?php

namespace Admin\STMath\Student;

use Admin\ModelAbstract\ServiceAbstract as ServiceAbstract;
use Admin\STMath\Student\Entity as STMathStudent;

class Service extends ServiceAbstract
{

    public function create($data) {

        $entity = new STMathStudent();

        $entity->create($data);

        $entity = $this->table->save($entity);

        return $entity;

    }

}