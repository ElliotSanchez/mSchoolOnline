<?php

namespace Admin\Mclass;

use Admin\ModelAbstract\ServiceAbstract as ServiceAbstract;
use Admin\Mclass\Table as MclassTable;
use Admin\Mclass\Entity as Mclass;
use Admin\Account\Entity as Account;
use Admin\MclassStudent\Service as MclassStudentService;
use Admin\Student\Service as StudentService;
use Admin\Student\Entity as Student;

class Service extends ServiceAbstract {

    protected $mclassStudentService;
    protected $studentService;

    public function __construct(MclassTable $table, MclassStudentService $mclassStudentService, StudentService $studentService) {
        $this->table = $table;
        $this->mclassStudentService = $mclassStudentService;
        $this->studentService = $studentService;
    }

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

    public function addStudentToMclass(Student $student, Mclass $mclass) {

        $this->mclassStudentService->create(array('student_id' => $student->id, 'mclass_id' => $mclass->id));

    }

    public function removeStudentFromMclass(Student $student, Mclass $mclass) {

        $this->mclassStudentService->table->deleteWith(['student_id' => $student->id, 'mclass_id' => $mclass->id]);

    }

    public function getStudentsAssignedToMclass(Mclass $mclass) {

        $mclassStudents = $this->mclassStudentService->table->fetchWith(['mclass_id' => $mclass->id]);

        $studentIds = [];

        foreach ($mclassStudents as $mclassStudent) $studentIds[] = $mclassStudent->studentId;

        return $this->studentService->get($studentIds);

    }

    public function getStudentsAvailableToMclass(Mclass $mclass) {

        $sql = $this->studentService->table->getSql();

        $select = $sql->select();

        $select->join('mclasses_students', 'mclasses_students.student_id = students.id', [], 'left'); //\Zend\Db\Sql\Select::SQL_STAR
        $select->where(['students.account_id = ?' => $mclass->accountId]);
        $select->where('mclasses_students.id IS NULL');

        return iterator_to_array($this->studentService->table->fetchWith($select));

    }


}