<?php

namespace Admin\Iready;

use Admin\ModelAbstract\EntityAbstract;

class Entity extends EntityAbstract {

    public $importFilename;
    public $importedAt;
    public $studentId;
    public $lastName;
    public $firstName;
    public $studentNumber;
    public $studentGrade;
    public $academicYear;
    public $school;
    public $subject;
    public $diagnosticGain;
    public $diagnosticCompletions;
    public $diagnosticCompletionDate;
    public $diagnosticOverallScaleScore;
    public $diagnosticOverallPlacement1;
    public $diagnosticNotes1;


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
        $this->studentNumber = (!empty($data['student_number'])) ? $data['student_number'] : null;
        $this->studentGrade = (!empty($data['student_grade'])) ? $data['student_grade'] : null;
        $this->academicYear = (!empty($data['academic_year'])) ? $data['academic_year'] : null;
        $this->school = (!empty($data['school'])) ? $data['school'] : null;
        $this->subject = (!empty($data['subject'])) ? $data['subject'] : null;
        $this->diagnosticGain = (!empty($data['diagnostic_gain'])) ? $data['diagnostic_gain'] : null;
        $this->diagnosticCompletions = (!empty($data['diagnostic_completions'])) ? $data['diagnostic_completions'] : null;
        $this->diagnosticCompletionDate = (!empty($data['diagnostic_completion_date'])) ? new \DateTime($data['diagnostic_completion_date']) : null;
        $this->diagnosticOverallScaleScore = (!empty($data['diagnostic_overall_scale_score'])) ? $data['diagnostic_overall_scale_score'] : null;
        $this->diagnosticOverallPlacement1 = (!empty($data['diagnostic_overall_placement_1'])) ? $data['diagnostic_overall_placement_1'] : null;
        $this->diagnosticNotes1 = (!empty($data['diagnostic_notes_1'])) ? $data['diagnostic_notes_1'] : null;

    }

    public function exchangeArray(array $data)
    {

        if (!$this->id) $this->id = (isset($data['id']) && !empty($data['id'])) ? $data['id'] : null;

        $this->importFilename = (!empty($data['import_filename'])) ? $data['import_filename'] : $this->importFilename;
        $this->importedAt = (!empty($data['imported_at'])) ? new \DateTime($data['imported_at']) : $this->importedAt;
        $this->studentId = (!empty($data['student_id'])) ? $data['student_id'] : $this->studentId;
        $this->lastName = (!empty($data['last_name'])) ? $data['last_name'] : $this->lastName;
        $this->firstName = (!empty($data['first_name'])) ? $data['first_name'] : $this->firstName;
        $this->studentNumber = (!empty($data['student_number'])) ? $data['student_number'] : $this->studentNumber;
        $this->studentGrade = (!empty($data['student_grade'])) ? $data['student_grade'] : $this->studentGrade;
        $this->academicYear = (!empty($data['academic_year'])) ? $data['academic_year'] : $this->academicYear;
        $this->school = (!empty($data['school'])) ? $data['school'] : $this->school;
        $this->subject = (!empty($data['subject'])) ? $data['subject'] : $this->subject;
        $this->diagnosticGain = (!empty($data['diagnostic_gain'])) ? $data['diagnostic_gain'] : $this->diagnosticGain;
        $this->diagnosticCompletions = (!empty($data['diagnostic_completions'])) ? $data['diagnostic_completions'] : $this->diagnosticCompletions;
        $this->diagnosticCompletionDate = (!empty($data['diagnostic_completion_date'])) ? new \DateTime($data['diagnostic_completion_date']) : $this->diagnosticCompletionDate;
        $this->diagnosticOverallScaleScore = (!empty($data['diagnostic_overall_scale_score'])) ? $data['diagnostic_overall_scale_score'] : $this->diagnosticOverallScaleScore;
        $this->diagnosticOverallPlacement1 = (!empty($data['diagnostic_overall_placement_1'])) ? $data['diagnostic_overall_placement_1'] : $this->diagnosticOverallPlacement1;
        $this->diagnosticNotes1 = (!empty($data['diagnostic_notes_1'])) ? $data['diagnostic_notes_1'] : $this->diagnosticNotes1;

        $this->exchangeDates($data);
    }

    public function toData() {

        $data = array(
            'import_filename' => $this->importFilename,
            'imported_at' => ($this->importedAt) ? ($this->importedAt->format('Y-m-d H:i:s')) : (null),
            'student_id' => $this->studentId,
            'last_name' => $this->lastName,
            'first_name' => $this->firstName,
            'student_number' => $this->studentNumber,
            'student_grade' => $this->studentGrade,
            'academic_year' => $this->academicYear,
            'school' => $this->school,
            'subject' => $this->subject,
            'diagnostic_gain' => $this->diagnosticGain,
            'diagnostic_completions' => $this->diagnosticCompletions,
            'diagnostic_completion_date' => ($this->diagnosticCompletionDate) ? ($this->diagnosticCompletionDate->format('Y-m-d H:i:s')) : (null),
            'diagnostic_overall_scale_score' => $this->diagnosticOverallScaleScore,
            'diagnostic_overall_placement_1' => $this->diagnosticOverallPlacement1,
            'diagnostic_notes_1' => $this->diagnosticNotes1,
        );

        return $data;

    }

    public function getArrayCopy() {
        return $this->toData();
    }

}