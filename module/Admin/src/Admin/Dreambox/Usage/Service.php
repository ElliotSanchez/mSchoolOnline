<?php

namespace Admin\Dreambox\Usage;

use Admin\ModelAbstract\ServiceAbstract as ServiceAbstract;
use Admin\Dreambox\Usage\Entity as DreamboxUsage;

class Service extends ServiceAbstract
{

    public function create($data) {

        $dreamboxUsage = new DreamboxUsage();

        $dreamboxUsage->create($data);

        $dreamboxUsage = $this->table->save($dreamboxUsage);

        return $dreamboxUsage;

    }
}