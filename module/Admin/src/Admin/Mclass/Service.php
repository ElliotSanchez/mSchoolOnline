<?php

namespace Admin\Mclass;

use Admin\ModelAbstract\ServiceAbstract as ServiceAbstract;
use Admin\Mclass\Table as MclassTable;
use Admin\Mclass\Entity as Mclass;
use Admin\Account\Entity as Account;
use Admin\School\Service as SchoolService;
use Admin\MclassStudent\Service as MclassStudentService;
use Admin\MclassTeacher\Service as MclassTeacherService;
use Admin\Student\Service as StudentService;
use Admin\Student\Entity as Student;
use Admin\Teacher\Service as TeacherService;
use Admin\Teacher\Entity as Teacher;

class Service extends ServiceAbstract {

    protected $mclassStudentService;
    protected $studentService;
    protected $teacherService;
    protected $schoolService;

    public function __construct(MclassTable $table, MclassStudentService $mclassStudentService, MclassTeacherService $mclassTeacherService, StudentService $studentService, TeacherService $teacherService, SchoolService $schoolService) {
        $this->table = $table;
        $this->mclassStudentService = $mclassStudentService;
        $this->mclassTeacherService = $mclassTeacherService;
        $this->studentService = $studentService;
        $this->teacherService = $teacherService;
        $this->schoolService = $schoolService;
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

        // TODO GET THIS QUERY RIGHT. THE OTHER CODE WON'T SCALE.
//        $sql = $this->studentService->table->getSql();
//
//        $select = $sql->select();
//
//        $select->join('mclasses_students', 'mclasses_students.student_id = students.id', [], 'left'); //\Zend\Db\Sql\Select::SQL_STAR
//        $select->where(['students.account_id = ?' => $mclass->accountId]);
//        $select->where('mclasses_students.id IS NULL');
//
//        return iterator_to_array($this->studentService->table->fetchWith($select));

        $assignedStudents = $this->getStudentsAssignedToMclass($mclass);
        $school = $this->schoolService->get($mclass->schoolId);
        $atSchool = $this->studentService->getForSchool($school);

        $assignedIds = [];

        foreach ($assignedStudents as $student) $assignedIds[] = $student->id;

        $available = [];

        foreach ($atSchool as $student) {
            if (!in_array($student->id, $assignedIds)) {
                $available[] = $student;
            }
        }

        return $available;

    }

    public function addTeacherToMclass(Teacher $teacher, Mclass $mclass) {

        $this->mclassTeacherService->create(array('teacher_id' => $teacher->id, 'mclass_id' => $mclass->id));

    }

    public function removeTeacherFromMclass(Teacher $teacher, Mclass $mclass) {

        $this->mclassTeacherService->table->deleteWith(['teacher_id' => $teacher->id, 'mclass_id' => $mclass->id]);

    }
    
    public function getTeachersAssignedToMclass(Mclass $mclass) {

        $mclassTeachers = $this->mclassTeacherService->table->fetchWith(['mclass_id' => $mclass->id]);

        $teacherIds = [];

        foreach ($mclassTeachers as $mclassTeacher) $teacherIds[] = $mclassTeacher->teacherId;

        return $this->teacherService->get($teacherIds);

    }

    public function getTeachersAvailableToMclass(Mclass $mclass) {

        $assignedTeachers = $this->getTeachersAssignedToMclass($mclass);
        $school = $this->schoolService->get($mclass->schoolId);
        $atSchool = $this->teacherService->getForSchool($school);

        $assignedIds = [];

        foreach ($assignedTeachers as $teacher) $assignedIds[] = $teacher->id;

        $available = [];

        foreach ($atSchool as $teacher) {
            if (!in_array($teacher->id, $assignedIds)) {
                $available[] = $teacher;
            }
        }

        return $available;

    }

    public function getMclassesForTeacher(Teacher $teacher) {

        $sql = $this->table->getSql();

        $select = $sql->select();

        $select->join('mclasses_teachers', 'mclasses_teachers.mclass_id = mclasses.id', []);
        $select->where(['mclasses_teachers.teacher_id = ?' => $teacher->id]);

        return iterator_to_array($this->table->fetchWith($select));

    }

    public function getStudentsAssignedToTeacher(Teacher $teacher) {

        $assignedStudents = [];

        $mclasses = $this->getMclassesForTeacher($teacher);

        foreach ($mclasses as $mclass) {

            $students = $this->getStudentsAssignedToMclass($mclass);

            foreach ($students as $student) {
                $assignedStudents[$student->id] = $student;
            }

        }

        function studentNameCompare($a, $b)
        {
            return strcmp($a->lastName.$a->firstName, $b->lastName.$b->firstName);
        }

        usort($assignedStudents, "studentNameCompare");

        return $assignedStudents;


    }
}