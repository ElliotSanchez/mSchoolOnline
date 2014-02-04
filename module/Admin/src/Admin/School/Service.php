<?php

namespace Admin\School;

use Admin\ModelAbstract\ServiceAbstract as ServiceAbstract;
use Admin\School\Entity as School;
use Admin\Account\Entity as Account;

class Service extends ServiceAbstract {

    public function create($data) {

        $school = new School();

        $school->create($data);

        $school = $this->table->save($school);

        return $school;

    }

    public function getForAccount(Account $account) {

        return $this->table->fetchWith(array('account_id' => $account->id));

    }

}