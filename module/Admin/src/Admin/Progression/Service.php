<?php

namespace Admin\Progression;

use Admin\ModelAbstract\ServiceAbstract as ServiceAbstract;
use Admin\Progression\Entity as Progression;
use Admin\Student\Entity as Student;

class Service extends ServiceAbstract
{

    protected $resourceService;
    protected $studentService;

    public function create($data) {

        $progression = new Progression();

        $progression->create($data);

        $progression = $this->table->save($progression);

        return $progression;

    }

//    public function getLatestProgression(Student $student) {
//
//        $select = $this->table->getSql()->select();
//        $select->where(array(
//            'student_id = ?' => $student->id,
//        ))->order(array('activity_date DESC'))->limit(1);
//
//        $results = $this->table->fetchWith($select);
//
//        return $results->current();
//
//    }

    protected function inactivateInProgressProgressions(Student $student) {

        $where = new \Zend\Db\Sql\Where();

        $where->equalTo('student_id', $student->id)
            ->equalTo('is_active', 1)
            ->equalTo('is_complete', 0);

        return $this->table->update(array('is_active' => 0), $where);

    }

}