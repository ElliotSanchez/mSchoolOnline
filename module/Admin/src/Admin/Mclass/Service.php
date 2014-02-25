<?php

namespace Admin\Mclass;

use Admin\ModelAbstract\ServiceAbstract as ServiceAbstract;
use Admin\Mclass\Entity as Mclass;
use Admin\Account\Entity as Account;

class Service extends ServiceAbstract {

    public function create($data) {

        $mclass = new Mclass();

        $mclass->create($data);

        $mclass = $this->table->save($mclass);

        return $mclass;

    }

    public function getForAccount(Account $account) {

        return $this->table->fetchWith(array('account_id' => $account->id));

    }

    public function getForSchool(School $school) {

        return $this->table->fetchWith(array('school_id' => $school->id));

    }

}