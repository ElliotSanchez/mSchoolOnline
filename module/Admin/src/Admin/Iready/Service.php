<?php

namespace Admin\Iready;

use Admin\ModelAbstract\ServiceAbstract as ServiceAbstract;
use Admin\Iready\Entity as Iready;

class Service extends ServiceAbstract
{

    public function create($data) {

        $iready = new Iready();

        $iready->create($data);

        $iready = $this->table->save($iready);

        return $iready;

    }
}