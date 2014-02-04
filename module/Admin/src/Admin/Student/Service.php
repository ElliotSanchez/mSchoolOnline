<?php

namespace Admin\Student;

use Admin\ModelAbstract\ServiceAbstract as ServiceAbstract;
use Admin\Student\Entity as Student;
use Admin\Account\Entity as Account;
use Admin\School\Entity as School;

class Service extends ServiceAbstract {

    public function create($data) {

        $student = new Student();

        $student->create($data);

        $student = $this->table->save($student);

        return $student;

    }

    public function getForAccount(Account $account) {

        return $this->table->fetchWith(array('account_id' => $account->id));

    }

    public function getForSchool(School $school) {

        return $this->table->fetchWith(array('school_id' => $school->id));

    }

}