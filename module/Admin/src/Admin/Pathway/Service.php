<?php

namespace Admin\Pathway;

use Admin\ModelAbstract\ServiceAbstract as ServiceAbstract;
use Admin\Pathway\Entity as Pathway;

class Service extends ServiceAbstract
{

    public function create($data) {

        $pathway = new Pathway();

        if (!isset($data['is_active'])) $data['is_active'] = 1;

        $pathway->create($data);

        $pathway = $this->table->save($pathway);

        return $pathway;

    }

}