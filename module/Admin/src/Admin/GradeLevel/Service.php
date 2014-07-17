<?php

namespace Admin\GradeLevel;

use Admin\ModelAbstract\ServiceAbstract as ServiceAbstract;
use Admin\GradeLevel\Entity as Account;

class Service extends ServiceAbstract {

    public function create($data) {

        $account = new GradeLevel();

        $account->create($data);

        $account = $this->table->save($account);

        return $account;

    }

    public function getOrdered() {
        return $this->table->fetchWith($this->table->getSql()->select()->order(['sort_order ASC']));
    }

    public function getMap() {
        $gradeLevels = $this->getOrdered();
        $map = [];
        foreach ($gradeLevels as $gradeLevel) {
            $map[$gradeLevel->id] = $gradeLevel;
        }

        return $map;

    }

    /** Results can be used to match a value like '2' to the '2nd' grade model */
    public function getNumericMap() {
        $gradeLevels = $this->getOrdered();
        $map = [];
        foreach ($gradeLevels as $gradeLevel) {
            $map[$gradeLevel->numericValue()] = $gradeLevel;
        }

        return $map;

    }

}