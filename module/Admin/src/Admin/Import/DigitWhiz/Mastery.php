<?php

namespace Admin\Import\DigitWhiz;

use Admin\Student\Service as StudentService;
use Admin\DigitWhiz\Mastery\Service as DigitWhizMasteryService;
use \Dropbox\Client as Dropbox;

class Mastery {

    protected $dropbox;
    protected $digitWhizMasteryService;
    protected $studentService;

    protected $originalFilename;
    protected $filename;

    protected $importDate;
    protected $importPath;

    public function __construct(DigitWhizMasteryService $DigitWhizMasteryService) {

        $this->digitWhizMasteryService = $DigitWhizMasteryService;
        $this->importDate = new \DateTime();
        $this->importPath = '/digitwhiz/import/mastery';
        $this->completedPath = '/digitwhiz/completed/mastery';
    }

    public function setDropbox(Dropbox $dropbox) {
        $this->dropbox = $dropbox;
    }

    public function setStudentService(StudentService $studentService) {
        $this->studentService = $studentService;
    }

    public function import() {

        $entries = $this->dropbox->getMetadataWithChildren($this->importPath);

        foreach($entries['contents'] as $child) {

            $cp = $child['path'];
            $cp = htmlspecialchars($cp);
            $filename = basename($cp);

            if ($filename == 'imported') continue;
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

        $STUDENT_NAME = 'A';
        $MULTIPLICATION_PLW = 'B';
        $MULTIPLICATION_CURRENT = 'C';
        $DIVISION_PLW = 'D';
        $DIVISION_CURRENT = 'E';
        $INTEGER_PLW = 'F';
        $INTEGER_CURRENT = 'G';
        $LIKE_TERMS_PLW = 'H';
        $LIKE_TERMS_CURRENT = 'I';
        $EQUATIONS_PLW = 'J';
        $EQUATIONS_CURRENT = 'K';

        foreach ($rowIterator as $row) {

            $cellIterator = $row->getCellIterator();
            $cellIterator->setIterateOnlyExistingCells(false); // Loop all cells, even if it is not set

            $a1Value = trim($objPHPExcel->getActiveSheet()->getCell('A'.$row->getRowIndex())->getValue());

            // CHECK FOR STUDENT DATA ROW
            if (!strlen($a1Value)) continue;
            if ($a1Value == 'Student Name') continue;
            if ($a1Value == 'PLW') continue;

            $data = [];
            $Mastery = [];

            foreach ($cellIterator as $cell) {
                switch ($cell->getColumn()) {
                    case $STUDENT_NAME: $data['student_name'] = $cell->getValue(); break;
                    case $MULTIPLICATION_PLW: $data['multiplication_plw'] = trim($cell->getValue()); break;
                    case $MULTIPLICATION_CURRENT: $data['multiplication_current'] = trim($cell->getValue()); break;
                    case $DIVISION_PLW: $data['division_plw'] = trim($cell->getValue()); break;
                    case $DIVISION_CURRENT: $data['division_current'] = trim($cell->getValue()); break;
                    case $INTEGER_PLW: $data['integer_plw'] = trim($cell->getValue()); break;
                    case $INTEGER_CURRENT: $data['integer_current'] = trim($cell->getValue()); break;
                    case $LIKE_TERMS_PLW: $data['like_terms_plw'] = trim($cell->getValue()); break;
                    case $LIKE_TERMS_CURRENT: $data['like_terms_current'] = trim($cell->getValue()); break;
                    case $EQUATIONS_PLW: $data['equations_plw'] = trim($cell->getValue()); break;
                    case $EQUATIONS_CURRENT: $data['equations_current'] = trim($cell->getValue()); break;
                }
            }

            // FIND STUDENT
            $student = null;

            $nameParts = explode(',', $data['student_name']);
            $mname = trim($nameParts[0]);

            if (strlen($mname))
                $student = $this->studentService->getWithStudentMname($mname);

            if ($student) {

                $data['import_filename'] = $this->originalFilename;
                $data['imported_at'] = $this->importDate->format('Y-m-d H:i:s');
                $data['student_id'] = $student->id;

                $digitwhizMastery = $this->digitWhizMasteryService->create($data);

            } else {
                // TODO NOT HANDLING MISSING STUDENT
            }

        }

    }

    protected function cleanUp() {

        $importCompletedPath = $this->completedPath . '/' .$this->importDate->format('Ymd_His');

        $metadata = $this->dropbox->getMetadata($importCompletedPath);

        if ($metadata == null) {
            $this->dropbox->createFolder($importCompletedPath);
        }

        $this->dropbox->move($this->originalFilename, $importCompletedPath . '/' . basename($this->originalFilename));

    }

}