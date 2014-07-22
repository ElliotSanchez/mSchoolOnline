<?php

namespace Admin\Stats;

use Admin\Import\DigitWhiz\Time;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\Sql\Sql as Sql;
use Zend\Db\Sql\Select as Select;
use Admin\Mclass\Entity as Mclass;
use Admin\Stats\TimeOnMath as TimeOnMath;

class Teacher
{

    protected $dbAdapter;

    public function __construct(AdapterInterface $dbAdapter) {
        $this->dbAdapter = $dbAdapter;
    }

    public function getTimeOnMath(Mclass $mclass) {

        //echo $select->getSqlString($this->dbAdapter->getPlatform());

        $tom = new TimeOnMath();

        $this->dbAdapter;

        try {

            $sql = new Sql($this->dbAdapter);

            // DIGIT WHIZ - TIME
            $digitwhizTimeSelect = $sql->select(['sourcetable' =>'digitwhiz_time_total_seconds'])->columns([
                'student_id' => 'student_id',
                'total_seconds' => 'digitwhiz_time',
            ])
                ->join('mclasses_students', 'mclasses_students.student_id = sourcetable.student_id', [])
                ->join('students', 'mclasses_students.student_id = students.id', ['last_name', 'first_name'])
                ->where(['mclasses_students.mclass_id = ?' => $mclass->id]);

            // DREAMBOX - USAGE
            $dreamboxUsageSelect = $sql->select(['sourcetable' =>'dreambox_usage_total_seconds'])->columns([
                'student_id' => 'student_id',
                'total_seconds' => 'total_seconds',
            ])
                ->join('mclasses_students', 'mclasses_students.student_id = sourcetable.student_id', [])
                ->join('students', 'mclasses_students.student_id = students.id', ['last_name', 'first_name'])
                ->where(['mclasses_students.mclass_id = ?' => $mclass->id]);

            // STMATH - USAGE
            $stmmathUsageSelect = $sql->select(['sourcetable' =>'stmath_usage_total_seconds'])->columns([
                'student_id' => 'student_id',
                'total_seconds' => 'total_seconds',
            ])
                ->join('mclasses_students', 'mclasses_students.student_id = sourcetable.student_id', [])
                ->join('students', 'mclasses_students.student_id = students.id', ['last_name', 'first_name'])
                ->where(['mclasses_students.mclass_id = ?' => $mclass->id]);

            // TTM - OVERVIEW
            $ttmOverviewSelect = $sql->select(['sourcetable' =>'ttm_overview_total_seconds'])->columns([
                'student_id' => 'student_id',
                'total_seconds' => 'total_seconds',
            ])
                ->join('mclasses_students', 'mclasses_students.student_id = sourcetable.student_id', [])
                ->join('students', 'mclasses_students.student_id = students.id', ['last_name', 'first_name'])
                ->where(['mclasses_students.mclass_id = ?' => $mclass->id]);

            $this->processTimeData($sql, $digitwhizTimeSelect, $tom);
            $this->processTimeData($sql, $dreamboxUsageSelect, $tom);
            $this->processTimeData($sql, $stmmathUsageSelect, $tom);
            $this->processTimeData($sql, $ttmOverviewSelect, $tom);

            return $tom;

        } catch (\Exception $e) {
            //var_dump($e);
        }

    }

    private function processTimeData(Sql $sql, Select $select, TimeOnMath $tom) {

        $statement = $sql->prepareStatementForSqlObject($select);

        $results = $statement->execute();

        foreach ($results as $r) {
            $tom->add($r['student_id'], $r['last_name'], $r['first_name'], $r['total_seconds']);
        }

    }

}