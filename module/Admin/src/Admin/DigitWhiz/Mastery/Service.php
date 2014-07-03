<?php

namespace Admin\DigitWhiz\Mastery;

use Admin\ModelAbstract\ServiceAbstract as ServiceAbstract;
use Admin\DigitWhiz\Mastery\Entity as DigitWhizMastery;

class Service extends ServiceAbstract
{

    public function create($data) {

        $entity = new DigitWhizMastery();

        $entity->create($data);

        $entity = $this->table->save($entity);

        return $entity;

    }

}