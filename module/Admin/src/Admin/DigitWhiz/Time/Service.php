<?php

namespace Admin\DigitWhiz\Time;

use Admin\ModelAbstract\ServiceAbstract as ServiceAbstract;
use Admin\DigitWhiz\Time\Entity as DigitWhizTime;

class Service extends ServiceAbstract
{

    public function create($data) {

        $entity = new DigitWhizTime();

        $entity->create($data);

        $entity = $this->table->save($entity);

        return $entity;

    }

}