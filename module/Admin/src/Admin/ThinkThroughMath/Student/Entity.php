<?php

namespace Admin\ThinkThroughMath\Student;

use Admin\ModelAbstract\EntityAbstract;

class Entity extends EntityAbstract {

    public $importFilename;
    public $importedAt;
    public $downloadDate;
    public $studentId;

    public $studentName;
    public $gradeName;
    public $classroomName;
    public $pathwayName;
    public $gradeLevelDeviation;
    public $lessonName;
    public $type;
    public $testedOut;
    public $passed;
    public $preQuizScore;
    public $postQuizScore;
    public $timeOnSystem;
    public $dateStarted;
    public $dateCompleted;

    public function filename() {
        return basename($this->importFilename);
    }

    public function create($data) {

        parent::create($data);

        $this->importFilename = (!empty($data['import_filename'])) ? $data['import_filename'] : null;
        $this->importedAt = (!empty($data['imported_at'])) ? new \DateTime($data['imported_at']) : null;
        $this->downloadDate = (!empty($data['download_date'])) ? new \DateTime($data['download_date']) : null;
        $this->studentId = (!empty($data['student_id'])) ? $data['student_id'] : null;
        $this->studentName = (!empty($data['student_name'])) ? $data['student_name'] : null;
        $this->gradeName = (!empty($data['grade_name'])) ? $data['grade_name'] : null;
        $this->classroomName = (!empty($data['classroom_name'])) ? $data['classroom_name'] : null;
        $this->pathwayName = (!empty($data['pathway_name'])) ? $data['pathway_name'] : null;
        $this->gradeLevelDeviation = (!empty($data['grade_level_deviation'])) ? $data['grade_level_deviation'] : null;
        $this->lessonName = (!empty($data['lesson_name'])) ? $data['lesson_name'] : null;
        $this->type = (!empty($data['type'])) ? $data['type'] : null;
        $this->testedOut = (!empty($data['tested_out'])) ? $data['tested_out'] : null;
        $this->passed = (!empty($data['passed'])) ? $data['passed'] : null;
        $this->preQuizScore = (!empty($data['pre_quiz_score'])) ? $data['pre_quiz_score'] : null;
        $this->postQuizScore = (!empty($data['post_quiz_score'])) ? $data['post_quiz_score'] : null;
        $this->timeOnSystem = (!empty($data['time_on_system'])) ? $data['time_on_system'] : null;
        $this->dateStarted = (!empty($data['date_started'])) ? $data['date_started'] : null;
        $this->dateCompleted = (!empty($data['date_completed'])) ? $data['date_completed'] : null;

    }

    public function exchangeArray(array $data)
    {

        if (!$this->id) $this->id = (isset($data['id']) && !empty($data['id'])) ? $data['id'] : null;

        $this->importFilename = (!empty($data['import_filename'])) ? $data['import_filename'] : $this->importFilename;
        $this->importedAt = (!empty($data['imported_at'])) ? new \DateTime($data['imported_at']) : $this->importedAt;
        $this->downloadDate = (!empty($data['download_date'])) ? new \DateTime($data['download_date']) : null;
        $this->studentId = (!empty($data['student_id'])) ? $data['student_id'] : $this->studentId;
        $this->studentName = (!empty($data['student_name'])) ? $data['student_name'] : $this->studentName;
        $this->gradeName = (!empty($data['grade_name'])) ? $data['grade_name'] : $this->gradeName;
        $this->classroomName = (!empty($data['classroom_name'])) ? $data['classroom_name'] : $this->classroomName;
        $this->pathwayName = (!empty($data['pathway_name'])) ? $data['pathway_name'] : $this->pathwayName;
        $this->gradeLevelDeviation = (!empty($data['grade_level_deviation'])) ? $data['grade_level_deviation'] : $this->gradeLevelDeviation;
        $this->lessonName = (!empty($data['lesson_name'])) ? $data['lesson_name'] : $this->lessonName;
        $this->type = (!empty($data['type'])) ? $data['type'] : $this->type;
        $this->testedOut = (!empty($data['tested_out'])) ? $data['tested_out'] : $this->testedOut;
        $this->passed = (!empty($data['passed'])) ? $data['passed'] : $this->passed;
        $this->preQuizScore = (!empty($data['pre_quiz_score'])) ? $data['pre_quiz_score'] : $this->preQuizScore;
        $this->postQuizScore = (!empty($data['post_quiz_score'])) ? $data['post_quiz_score'] : $this->postQuizScore;
        $this->timeOnSystem = (!empty($data['time_on_system'])) ? $data['time_on_system'] : $this->timeOnSystem;
        $this->dateStarted = (!empty($data['date_started'])) ? $data['date_started'] : $this->dateStarted;
        $this->dateCompleted = (!empty($data['date_completed'])) ? $data['date_completed'] : $this->dateCompleted;

        $this->exchangeDates($data);
    }

    public function toData() {

        $data = array(
            'import_filename' => $this->importFilename,
            'imported_at' => ($this->importedAt) ? ($this->importedAt->format('Y-m-d H:i:s')) : (null),
            'download_date' => ($this->downloadDate) ? ($this->downloadDate->format('Y-m-d H:i:s')) : (null),
            'student_id' => $this->studentId,
            'student_name' => $this->studentName,
            'grade_name' => $this->gradeName,
            'classroom_name' => $this->classroomName,
            'pathway_name' => $this->pathwayName,
            'grade_level_deviation' => $this->gradeLevelDeviation,
            'lesson_name' => $this->lessonName,
            'type' => $this->type,
            'tested_out' => $this->testedOut,
            'passed' => $this->passed,
            'pre_quiz_score' => $this->preQuizScore,
            'post_quiz_score' => $this->postQuizScore,
            'time_on_system' => $this->timeOnSystem,
            'date_started' => $this->dateStarted,
            'date_completed' => $this->dateCompleted,
        );

        return $data;

    }

    public function getArrayCopy() {
        return $this->toData();
    }

}