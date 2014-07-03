<?php

namespace Admin\STMath\Usage;

use Admin\ModelAbstract\EntityAbstract;

class Entity extends EntityAbstract {

    public $importFilename;
    public $importedAt;
    public $studentId;

    public $scd;
    public $firstName;
    public $lastName;
    public $avgTimeWeek;
    public $avgProgressWeek;
    public $totalTime;
    public $syllabusProgress;
    public $firstLogin;

    public function filename() {
        return basename($this->importFilename);
    }

    public function create($data) {

        parent::create($data);

        $this->importFilename = (!empty($data['import_filename'])) ? $data['import_filename'] : null;
        $this->importedAt = (!empty($data['imported_at'])) ? new \DateTime($data['imported_at']) : null;
        $this->studentId = (!empty($data['student_id'])) ? $data['student_id'] : null;

        $this->scd = (strlen($data['scd'])) ? ($data['scd']) : null;
        $this->firstName = (strlen($data['first_name'])) ? ($data['first_name']) : null;
        $this->lastName = (strlen($data['last_name'])) ? ($data['last_name']) : null;
        $this->avgTimeWeek = (strlen($data['avg_time_week'])) ? ($data['avg_time_week']) : null;
        $this->avgProgressWeek = (strlen($data['avg_progress_week'])) ? ($data['avg_progress_week']) : null;
        $this->totalTime = (strlen($data['total_time'])) ? ($data['total_time']) : null;
        $this->syllabusProgress = (strlen($data['syllabus_progress'])) ? ($data['syllabus_progress']) : null;
        $this->firstLogin = (strlen($data['first_login'])) ? ($data['first_login']) : null;

    }

    public function exchangeArray(array $data)
    {

        if (!$this->id) $this->id = (isset($data['id']) && !empty($data['id'])) ? $data['id'] : null;

        $this->importFilename = (!empty($data['import_filename'])) ? $data['import_filename'] : $this->importFilename;
        $this->importedAt = (!empty($data['imported_at'])) ? new \DateTime($data['imported_at']) : $this->importedAt;
        $this->studentId = (!empty($data['student_id'])) ? $data['student_id'] : $this->studentId;

        $this->scd = (strlen($data['scd'])) ? ($data['scd']) : $this->scd;
        $this->firstName = (strlen($data['first_name'])) ? ($data['first_name']) : $this->firstName;
        $this->lastName = (strlen($data['last_name'])) ? ($data['last_name']) : $this->lastName;
        $this->avgTimeWeek = (strlen($data['avg_time_week'])) ? ($data['avg_time_week']) : $this->avgTimeWeek;
        $this->avgProgressWeek = (strlen($data['avg_progress_week'])) ? ($data['avg_progress_week']) : $this->avgProgressWeek;
        $this->totalTime = (strlen($data['total_time'])) ? ($data['total_time']) : $this->totalTime;
        $this->syllabusProgress = (strlen($data['syllabus_progress'])) ? ($data['syllabus_progress']) : $this->syllabusProgress;
        $this->firstLogin = (strlen($data['first_login'])) ? ($data['first_login']) : $this->firstLogin;

        $this->exchangeDates($data);
    }

    public function toData() {

        $data = array(
            'import_filename' => $this->importFilename,
            'imported_at' => ($this->importedAt) ? ($this->importedAt->format('Y-m-d H:i:s')) : (null),
            'student_id' => $this->studentId,

            'scd' => $this->scd,
            'first_name' => $this->firstName,
            'last_name' => $this->lastName,
            'avg_time_week' => $this->avgTimeWeek,
            'avg_progress_week' => $this->avgProgressWeek,
            'total_time' => $this->totalTime,
            'syllabus_progress' => $this->syllabusProgress,
            'first_login' => $this->firstLogin,

        );

        return $data;

    }

    public function getArrayCopy() {
        return $this->toData();
    }

}