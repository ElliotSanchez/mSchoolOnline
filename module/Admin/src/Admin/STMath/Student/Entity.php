<?php

namespace Admin\STMath\Student;

use Admin\ModelAbstract\EntityAbstract;

class Entity extends EntityAbstract {

    public $importFilename;
    public $importedAt;
    public $downloadDate;
    public $studentId;

    public $firstName;
    public $lastName;


    public function filename() {
        return basename($this->importFilename);
    }

    public function create($data) {

        parent::create($data);

        $this->importFilename = (!empty($data['import_filename'])) ? $data['import_filename'] : null;
        $this->importedAt = (!empty($data['imported_at'])) ? new \DateTime($data['imported_at']) : null;
        $this->downloadDate = (!empty($data['download_date'])) ? new \DateTime($data['download_date']) : null;
        $this->studentId = (!empty($data['student_id'])) ? $data['student_id'] : null;

        $this->firstName = (strlen($data['first_name'])) ? ($data['first_name']) : null;
        $this->lastName = (strlen($data['last_name'])) ? ($data['last_name']) : null;

    }

    public function exchangeArray(array $data)
    {

        if (!$this->id) $this->id = (isset($data['id']) && !empty($data['id'])) ? $data['id'] : null;

        $this->importFilename = (!empty($data['import_filename'])) ? $data['import_filename'] : $this->importFilename;
        $this->importedAt = (!empty($data['imported_at'])) ? new \DateTime($data['imported_at']) : $this->importedAt;
        $this->downloadDate = (!empty($data['download_date'])) ? new \DateTime($data['download_date']) : null;
        $this->studentId = (!empty($data['student_id'])) ? $data['student_id'] : $this->studentId;

        $this->firstName = (strlen($data['first_name'])) ? ($data['first_name']) : $this->firstName;
        $this->lastName = (strlen($data['last_name'])) ? ($data['last_name']) : $this->lastName;


        $this->exchangeDates($data);
    }

    public function toData() {

        $data = array(
            'import_filename' => $this->importFilename,
            'imported_at' => ($this->importedAt) ? ($this->importedAt->format('Y-m-d H:i:s')) : (null),
            'download_date' => ($this->downloadDate) ? ($this->downloadDate->format('Y-m-d H:i:s')) : (null),
            'student_id' => $this->studentId,

//            'first_name' => $this->firstName,
//            'last_name' => $this->lastName,


        );

        return $data;

    }

    public function getArrayCopy() {
        return $this->toData();
    }

}