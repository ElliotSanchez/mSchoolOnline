<?php

namespace Admin\DigitWhiz\Time;

use Admin\ModelAbstract\EntityAbstract;

class Entity extends EntityAbstract {

    public $importFilename;
    public $importedAt;
    public $studentId;

    public $studentName;
    public $plays;
    public $points;
    public $time;

    public function filename() {
        return basename($this->importFilename);
    }

    public function create($data) {

        parent::create($data);

        $this->importFilename = (!empty($data['import_filename'])) ? $data['import_filename'] : null;
        $this->importedAt = (!empty($data['imported_at'])) ? new \DateTime($data['imported_at']) : null;
        $this->studentId = (!empty($data['student_id'])) ? $data['student_id'] : null;

        $this->studentName = (!empty($data['student_name'])) ? $data['student_name'] : null;
        $this->plays = (strlen($data['plays'])) ? $data['plays'] : null;
        $this->points = (strlen($data['points'])) ? $data['points'] : null;
        $this->time = (strlen($data['dwtime'])) ? $data['dwtime'] : null;

    }

    public function exchangeArray(array $data)
    {

        if (!$this->id) $this->id = (isset($data['id']) && !empty($data['id'])) ? $data['id'] : null;

        $this->importFilename = (!empty($data['import_filename'])) ? $data['import_filename'] : $this->importFilename;
        $this->importedAt = (!empty($data['imported_at'])) ? new \DateTime($data['imported_at']) : $this->importedAt;
        $this->studentId = (!empty($data['student_id'])) ? $data['student_id'] : $this->studentId;

        $this->studentName = (!empty($data['student_name'])) ? $data['student_name'] : $this->studentName;
        $this->plays = (strlen($data['plays'])) ? $data['plays'] : $this->plays;
        $this->points = (strlen($data['points'])) ? $data['points'] : $this->points;
        $this->time = (strlen($data['dwtime'])) ? $data['dwtime'] : $this->time;


        $this->exchangeDates($data);
    }

    public function toData() {

        $data = array(
            'import_filename' => $this->importFilename,
            'imported_at' => ($this->importedAt) ? ($this->importedAt->format('Y-m-d H:i:s')) : (null),
            'student_id' => $this->studentId,

            'student_name' => $this->studentName,
            'plays' => $this->plays,
            'points' => $this->points,
            'dwtime' => $this->time,

        );

        return $data;

    }

    public function getArrayCopy() {
        return $this->toData();
    }

}