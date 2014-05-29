<?php

namespace Admin\MclassStudent;

use Admin\ModelAbstract\ServiceAbstract as ServiceAbstract;

use Admin\MclassStudent\Entity as MclassStudent;
use Admin\MclassStudent\Table as MclassStudentTable;

class Service extends ServiceAbstract
{

    public function __construct(MclassStudentTable $mclassStudentTable) {
        parent::__construct($mclassStudentTable);
    }

    public function create($data) {

        $mclassStudent = new MclassStudent();

        $mclassStudent->create($data);

        $mclassStudent = $this->table->save($mclassStudent);

        return $mclassStudent;

    }

}