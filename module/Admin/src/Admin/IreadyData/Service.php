<?php

namespace Admin\IreadyData;

use Admin\ModelAbstract\ServiceAbstract as ServiceAbstract;
use Admin\IreadyData\Entity as IreadyData;

class Service extends ServiceAbstract
{

    public function create($data) {

        $ireadyData = new IreadyData();

        $ireadyData->create($data);

        $ireadyData = $this->table->save($ireadyData);

        return $ireadyData;

    }
}