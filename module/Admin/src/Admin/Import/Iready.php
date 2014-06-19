<?php

namespace Admin\Import;

use Admin\Student\Service as StudentService;
use Admin\Iready\Service as IreadyService;
use \Dropbox\Client as Dropbox;

class Iready {

    protected $dropbox;
    protected $ireadyService;
    protected $studentService;

    protected $originalFilename;
    protected $filename;

    public function __construct(IreadyService $ireadyService) {
        $this->ireadyService = $ireadyService;
        $this->importDate = new \DateTime();
    }

    public function setDropbox(Dropbox $dropbox) {
        $this->dropbox = $dropbox;
    }

    public function setStudentService(StudentService $studentService) {
        $this->studentService = $studentService;
    }

    public function import() {

        $entries = $this->dropbox->getMetadataWithChildren('/iready');

        foreach($entries['contents'] as $child) {

            $cp = $child['path'];
            $cp = htmlspecialchars($cp);
            $filename = basename($cp);

            if ($filename == 'completed') continue;

            $this->originalFilename = $cp;

            $fd = tmpfile();
            $metadata = $this->dropbox->getFile($cp, $fd);

            $fileMetadata = stream_get_meta_data($fd);
            $this->filename = $fileMetadata["uri"];

            $this->importFile();

            $this->cleanUp();

        }

    }

    protected function importFile() {

        $importFileName = $this->filename;

        $objPHPExcel = \PHPExcel_IOFactory::load($importFileName);

        $rowIterator = $objPHPExcel->getActiveSheet()->getRowIterator();

        $STUDENT_FNAME_COL = 'B';
        $STUDENT_LNAME_COL = 'A';
        $STUDENT_NUM_COL = 'C';
        $STUDENT_GRADE = 'D';
        $STUDENT_ACADEMIC_YEAR = 'E';
        $STUDENT_SCHOOL = 'F';
        $STUDENT_SUBJECT = 'G';
        $STUDENT_DIAGNOSTIC_GAIN = 'H';
        $STUDENT_DIAGNOSTIC_COMPLETIONS = 'I';
        $STUDENT_DIAGNOSTIC_COMPLETION_DATE = 'J';
        $STUDENT_DIAGNOSTIC_OVERALL_SCORE = 'K';
        $STUDENT_DIAGNOSTIC_OVERALL_PLACEMENT = 'L';
        $STUDENT_DIAGNOSTIC_NOTES = 'M';

        foreach ($rowIterator as $row) {

            $cellIterator = $row->getCellIterator();
            $cellIterator->setIterateOnlyExistingCells(false); // Loop all cells, even if it is not set

            if(1 == $row->getRowIndex ()) continue; //skip first row

            $data = [];

            foreach ($cellIterator as $cell) {
                switch ($cell->getColumn()) {
                    case $STUDENT_FNAME_COL: $data['first_name'] = trim($cell->getValue()); break;
                    case $STUDENT_LNAME_COL: $data['last_name'] = trim($cell->getValue()); break;
                    case $STUDENT_NUM_COL: $data['student_number'] = trim($cell->getValue()); break;
                    case $STUDENT_GRADE: $data['student_grade'] = trim($cell->getValue()); break;
                    case $STUDENT_ACADEMIC_YEAR: $data['academic_year'] = trim($cell->getValue()); break;
                    case $STUDENT_SCHOOL: $data['school'] = trim($cell->getValue()); break;
                    case $STUDENT_SUBJECT: $data['subject'] = trim($cell->getValue()); break;
                    case $STUDENT_DIAGNOSTIC_GAIN: $data['diagnostic_gain'] = trim($cell->getValue()); break;
                    case $STUDENT_DIAGNOSTIC_COMPLETIONS: $data['diagnostic_completions'] = trim($cell->getValue()); break;
                    case $STUDENT_DIAGNOSTIC_COMPLETION_DATE: $data['diagnostic_completion_date'] = trim($cell->getValue()); break;
                    case $STUDENT_DIAGNOSTIC_OVERALL_SCORE: $data['diagnostic_overall_scale_score'] = trim($cell->getValue()); break;
                    case $STUDENT_DIAGNOSTIC_OVERALL_PLACEMENT: $data['diagnostic_overall_placement_1'] = trim($cell->getValue()); break;
                    case $STUDENT_DIAGNOSTIC_NOTES: $data['diagnostic_notes_1'] = trim($cell->getValue()); break;
                }
            }

            // FIND STUDENT
            $student = null;

            if (!empty($data['student_number']))
                $student = $this->studentService->getWithStudentNumber($data['student_number']);

            if ($student) {

                $data['import_filename'] = $this->originalFilename;
                $data['imported_at'] = $this->importDate->format('Y-m-d H:i:s');
                $data['student_id'] = $student->id;

                $this->ireadyService->create($data);


            } else {
                // TODO NOT HANDLING MISSING STUDENT
            }

        }

    }

    protected function cleanUp() {

        $importCompletedPath = '/iready/completed/'.$this->importDate->format('Ymd_His');

        $metadata = $this->dropbox->getMetadata($importCompletedPath);

        if ($metadata == null) {
            $this->dropbox->createFolder($importCompletedPath);
        }

        $this->dropbox->move($this->originalFilename, $importCompletedPath . '/' . basename($this->originalFilename));

    }

}