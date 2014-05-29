<?php

namespace Admin\Teacher;

use Admin\ModelAbstract\ServiceAbstract as ServiceAbstract;
use Admin\Teacher\Entity as Teacher;
use Admin\Account\Entity as Account;
use Admin\School\Entity as School;

class Service extends ServiceAbstract {

    public function create($data) {

        $teacher = new Teacher();

        $teacher->create($data);

        $teacher = $this->table->save($teacher);

        return $teacher;

    }

    public function getForUsername($username) {

        return $this->table->fetchWith(['username' => $username])->current();

    }

    public function getForUsernameWithAccount($username, Account $account) {

        return $this->table->fetchWith(['username' => $username, 'account_id' => $account->id])->current();

    }

    public function getForAccount(Account $account) {

        return $this->table->fetchWith(array('account_id' => $account->id));

    }

    public function getForSchool(School $school) {

        return iterator_to_array($this->table->fetchWith(array('school_id' => $school->id)));

    }

}