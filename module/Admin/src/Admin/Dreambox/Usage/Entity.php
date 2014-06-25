<?php

namespace Admin\Dreambox\Usage;

use Admin\ModelAbstract\EntityAbstract;

class Entity extends EntityAbstract {

    public $importFilename;
    public $importedAt;
    public $studentId;
    public $lastName;
    public $firstName;
    public $studentGrade;
    public $teacherEmails;
    public $className;
    public $schoolName;
    public $intervention;
    public $sessions;
    public $timeOnTask; // HH:MM
    public $lessonsCompleted;
    public $uniqueLessonsCompleted;
    public $unitsCompleted;

    public function filename() {
        return basename($this->importFilename);
    }

    public function create($data) {

        parent::create($data);

        $this->importFilename = (!empty($data['import_filename'])) ? $data['import_filename'] : null;
        $this->importedAt = (!empty($data['imported_at'])) ? new \DateTime($data['imported_at']) : null;
        $this->studentId = (!empty($data['student_id'])) ? $data['student_id'] : null;
        $this->lastName = (!empty($data['last_name'])) ? $data['last_name'] : null;
        $this->firstName = (!empty($data['first_name'])) ? $data['first_name'] : null;

        $this->studentGrade = (!empty($data['student_grade'])) ? $data['student_grade'] : null;
        $this->teacherEmails = (!empty($data['teacher_emails'])) ? $data['teacher_emails'] : null;
        $this->className = (!empty($data['class_name'])) ? $data['class_name'] : null;
        $this->schoolName = (!empty($data['school_name'])) ? $data['school_name'] : null;
        $this->intervention = (!empty($data['intervention'])) ? $data['intervention'] : null;
        $this->sessions = (!empty($data['sessions'])) ? $data['sessions'] : null;
        $this->timeOnTask = (!empty($data['time_on_task'])) ? $data['time_on_task'] : null;
        $this->lessonsCompleted = (!empty($data['lessons_completed'])) ? $data['lessons_completed'] : 0;
        $this->uniqueLessonsCompleted = (!empty($data['unique_lessons_completed'])) ? $data['unique_lessons_completed'] : 0;
        $this->unitsCompleted = (!empty($data['units_completed'])) ? $data['units_completed'] : 0;

    }

    public function exchangeArray(array $data)
    {

        if (!$this->id) $this->id = (isset($data['id']) && !empty($data['id'])) ? $data['id'] : null;

        $this->importFilename = (!empty($data['import_filename'])) ? $data['import_filename'] : $this->importFilename;
        $this->importedAt = (!empty($data['imported_at'])) ? new \DateTime($data['imported_at']) : $this->importedAt;
        $this->studentId = (!empty($data['student_id'])) ? $data['student_id'] : $this->studentId;
        $this->lastName = (!empty($data['last_name'])) ? $data['last_name'] : $this->lastName;
        $this->firstName = (!empty($data['first_name'])) ? $data['first_name'] : $this->firstName;
        $this->studentGrade = (!empty($data['student_grade'])) ? $data['student_grade'] : $this->studentGrade;
        $this->teacherEmails = (!empty($data['teacher_emails'])) ? $data['teacher_emails'] : $this->teacherEmails;
        $this->className = (!empty($data['class_name'])) ? $data['class_name'] : $this->className;
        $this->schoolName = (!empty($data['school_name'])) ? $data['school_name'] : $this->schoolName;
        $this->intervention = (!empty($data['intervention'])) ? $data['intervention'] : $this->intervention;
        $this->sessions = (!empty($data['sessions'])) ? $data['sessions'] : $this->sessions;
        $this->timeOnTask = (!empty($data['time_on_task'])) ? $data['time_on_task'] : $this->timeOnTask;
        $this->lessonsCompleted = (!empty($data['lessons_completed'])) ? $data['lessons_completed'] : $this->lessonsCompleted;
        $this->uniqueLessonsCompleted = (!empty($data['unique_lessons_completed'])) ? $data['unique_lessons_completed'] : $this->uniqueLessonsCompleted;
        $this->unitsCompleted = (!empty($data['units_completed'])) ? $data['units_completed'] : $this->unitsCompleted;

        $this->exchangeDates($data);
    }

    public function toData() {

        $data = array(
            'import_filename' => $this->importFilename,
            'imported_at' => ($this->importedAt) ? ($this->importedAt->format('Y-m-d H:i:s')) : (null),
            'student_id' => $this->studentId,
            'last_name' => $this->lastName,
            'first_name' => $this->firstName,
            'student_grade' => $this->studentGrade,
            'teacher_emails' => $this->teacherEmails,
            'class_name' => $this->className,
            'school_name' => $this->schoolName,
            'intervention' => $this->intervention,
            'sessions' => $this->sessions,
            'time_on_task' => $this->timeOnTask,
            'lessons_completed' => $this->lessonsCompleted,
            'unique_lessons_completed' => $this->uniqueLessonsCompleted,
            'units_completed' => $this->unitsCompleted,
        );

        return $data;

    }

    public function getArrayCopy() {
        return $this->toData();
    }

}