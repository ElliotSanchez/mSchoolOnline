<?php

namespace Admin\Plan;

use Admin\ModelAbstract\ServiceAbstract as ServiceAbstract;
use Admin\Plan\Entity as Plan;

class Service extends ServiceAbstract
{

    public function create($data) {

        $plan = new Plan();

        if (!isset($data['is_active'])) $data['is_active'] = 1;

        $plan->create($data);

        $plan = $this->table->save($plan);

        return $plan;

    }

}