<?php

namespace Admin\Plan;

use Admin\ModelAbstract\ServiceAbstract as ServiceAbstract;
use Admin\Plan\Entity as Plan;

class Service extends ServiceAbstract
{

    public function create($data) {

        $plan = new Plan();

        $plan->create($data);

        $plan = $this->table->save($plan);

        return $plan;

    }

}