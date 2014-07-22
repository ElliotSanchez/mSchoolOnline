<?php

namespace Admin\Iready;

use Admin\ModelAbstract\ServiceAbstract as ServiceAbstract;
use Admin\Iready\Entity as Iready;
use Admin\Mclass\Entity as Mclass;

class Service extends ServiceAbstract
{

    public function create($data) {

        $iready = new Iready();

        $iready->create($data);

        $iready = $this->table->save($iready);

        return $iready;

    }

    public function getStudentPlacement(Mclass $mclass) {

//        SELECT student_id, students.last_name, students.first_name, diagnostic_overall_scale_score, MAX(download_date)
//        FROM iready
//        INNER JOIN students ON iready.student_id = students.id
//        GROUP BY student_id, students.last_name, students.first_name, diagnostic_overall_scale_score
//        ORDER BY diagnostic_overall_scale_score DESC, students.last_name, students.first_name

        $sql = $this->table->getSql();

        $select = $sql->select()->columns([
            'student_id' => 'student_id',
            'diagnostic_overall_scale_score' => 'diagnostic_overall_scale_score',
            'download_date' => new \Zend\Db\Sql\Expression('MAX(download_date)'),
        ]);

        $select->join('mclasses_students', 'mclasses_students.student_id = iready.student_id', []);
        $select->join('students', 'mclasses_students.student_id = students.id', [
            'last_name' => 'last_name',
            'first_name' => 'first_name',
        ]);
        $select->where(['mclasses_students.mclass_id = ?' => $mclass->id]);

        $select->group(['student_id', 'diagnostic_overall_scale_score']);
        $select->order(['diagnostic_overall_scale_score DESC', 'last_name ASC', 'first_name ASC']);

        //die($select->getSqlString($this->table->getAdapter()->getPlatform()));

        return $this->table->fetchWith($select);


    }

}