<?php

namespace Admin\Pathway;

use Admin\ModelAbstract\ServiceAbstract as ServiceAbstract;
use Admin\Pathway\Entity as Pathway;

class Service extends ServiceAbstract
{

    public function create($data) {

        $pathway = new Pathway();

        $pathway->create($data);

        $pathway = $this->table->save($pathway);

        return $pathway;

    }

}