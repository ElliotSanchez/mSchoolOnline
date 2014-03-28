<?php

namespace Admin\Progression;

use Admin\ModelAbstract\ServiceAbstract as ServiceAbstract;
use Admin\Progression\Entity as Progression;

class Service extends ServiceAbstract
{

    protected $resourceService;
    protected $studentService;

    public function create($data) {

        $progression = new Progression();

        $progression->create($data);

        $progression = $this->table->save($progression);

        return $progression;

    }

}