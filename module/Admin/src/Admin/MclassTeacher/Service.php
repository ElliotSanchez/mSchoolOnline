<?php

namespace Admin\MclassTeacher;

use Admin\ModelAbstract\ServiceAbstract as ServiceAbstract;

use Admin\MclassTeacher\Entity as MclassTeacher;
use Admin\MclassTeacher\Table as MclassTeacherTable;

class Service extends ServiceAbstract
{

    public function __construct(MclassTeacherTable $mclassTeacherTable) {
        parent::__construct($mclassTeacherTable);
    }

    public function create($data) {

        $mclassTeacher = new MclassTeacher();

        $mclassTeacher->create($data);

        $mclassTeacher = $this->table->save($mclassTeacher);

        return $mclassTeacher;

    }

}