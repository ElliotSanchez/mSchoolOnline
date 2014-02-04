<?php

namespace Admin\Teacher;

use Admin\ModelAbstract\ServiceAbstract as ServiceAbstract;
use Admin\Teacher\Entity as Teacher;

class Service extends ServiceAbstract {

    public function create($data) {

        $teacher = new Teacher();

        $teacher->create($data);

        $teacher = $this->table->save($teacher);

        return $teacher;

    }

    public function getForAccount(Account $account) {

        return $this->table->fetchWith(array('account_id' => $account->id));

    }

}