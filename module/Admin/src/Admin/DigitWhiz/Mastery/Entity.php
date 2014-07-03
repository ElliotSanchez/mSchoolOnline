<?php

namespace Admin\DigitWhiz\Mastery;

use Admin\ModelAbstract\EntityAbstract;

class Entity extends EntityAbstract {

    public $importFilename;
    public $importedAt;
    public $studentId;

    public $studentName;
    public $multiplicationPLW;
    public $multiplicationCurrent;
    public $divisionPLW;
    public $divisionCurrent;
    public $integerPLW;
    public $integerCurrent;
    public $likeTermsPLW;
    public $likeTermsCurrent;
    public $equationsPLW;
    public $equationsCurrent;


    public function filename() {
        return basename($this->importFilename);
    }

    public function create($data) {

        parent::create($data);

        $this->importFilename = (!empty($data['import_filename'])) ? $data['import_filename'] : null;
        $this->importedAt = (!empty($data['imported_at'])) ? new \DateTime($data['imported_at']) : null;
        $this->studentId = (!empty($data['student_id'])) ? $data['student_id'] : null;

        $this->studentName = (!empty($data['student_name'])) ? $data['student_name'] : null;
        $this->multiplicationPLW = (!empty($data['multiplication_plw'])) ? $data['multiplication_plw'] : null;
        $this->multiplicationCurrent = (!empty($data['multiplication_current'])) ? $data['multiplication_current'] : null;
        $this->divisionPLW = (!empty($data['division_plw'])) ? $data['division_plw'] : null;
        $this->divisionCurrent = (!empty($data['division_current'])) ? $data['division_current'] : null;
        $this->integerPLW = (!empty($data['integer_plw'])) ? $data['integer_plw'] : null;
        $this->integerCurrent = (!empty($data['integer_current'])) ? $data['integer_current'] : null;
        $this->likeTermsPLW = (!empty($data['like_terms_plw'])) ? $data['like_terms_plw'] : null;
        $this->likeTermsCurrent = (!empty($data['like_terms_current'])) ? $data['like_terms_current'] : null;
        $this->equationsPLW = (!empty($data['equations_plw'])) ? $data['equations_plw'] : null;
        $this->equationsCurrent = (!empty($data['equations_current'])) ? $data['equations_current'] : null;
    }

    public function exchangeArray(array $data)
    {

        if (!$this->id) $this->id = (isset($data['id']) && !empty($data['id'])) ? $data['id'] : null;

        $this->importFilename = (!empty($data['import_filename'])) ? $data['import_filename'] : $this->importFilename;
        $this->importedAt = (!empty($data['imported_at'])) ? new \DateTime($data['imported_at']) : $this->importedAt;
        $this->studentId = (!empty($data['student_id'])) ? $data['student_id'] : $this->studentId;

        $this->studentName = (!empty($data['student_name'])) ? $data['student_name'] : $this->studentName;
        $this->multiplicationPLW = (!empty($data['multiplication_plw'])) ? $data['multiplication_plw'] : $this->multiplicationPLW;
        $this->multiplicationCurrent = (!empty($data['multiplication_current'])) ? $data['multiplication_current'] : $this->multiplicationCurrent;
        $this->divisionPLW = (!empty($data['division_plw'])) ? $data['division_plw'] : $this->divisionPLW;
        $this->divisionCurrent = (!empty($data['division_current'])) ? $data['division_current'] : $this->divisionCurrent;
        $this->integerPLW = (!empty($data['integer_plw'])) ? $data['integer_plw'] : $this->integerPLW;
        $this->integerCurrent = (!empty($data['integer_current'])) ? $data['integer_current'] : $this->integerCurrent;
        $this->likeTermsPLW = (!empty($data['like_terms_plw'])) ? $data['like_terms_plw'] : $this->likeTermsPLW;
        $this->likeTermsCurrent = (!empty($data['like_terms_current'])) ? $data['like_terms_current'] : $this->likeTermsCurrent;
        $this->equationsPLW = (!empty($data['equations_plw'])) ? $data['equations_plw'] : $this->equationsPLW;
        $this->equationsCurrent = (!empty($data['equations_current'])) ? $data['equations_current'] : $this->equationsCurrent;

        $this->exchangeDates($data);
    }

    public function toData() {

        $data = array(
            'import_filename' => $this->importFilename,
            'imported_at' => ($this->importedAt) ? ($this->importedAt->format('Y-m-d H:i:s')) : (null),
            'student_id' => $this->studentId,

            'student_name' => $this->studentName,
            'multiplication_plw' => $this->multiplicationPLW,
            'multiplication_current' => $this->multiplicationCurrent,
            'division_plw' => $this->divisionPLW,
            'division_current' => $this->divisionCurrent,
            'integer_plw' => $this->integerPLW,
            'integer_current' => $this->integerCurrent,
            'like_terms_plw' => $this->likeTermsPLW,
            'like_terms_current' => $this->likeTermsCurrent,
            'equations_plw' => $this->equationsPLW,
            'equations_current' => $this->equationsCurrent,

        );

        return $data;

    }

    public function getArrayCopy() {
        return $this->toData();
    }

}