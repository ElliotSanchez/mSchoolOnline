<?php

namespace Admin\Import\DigitWhiz;

use Admin\Student\Service as StudentService;
use Admin\DigitWhiz\Time\Service as DigitWhizTimeService;
use \Dropbox\Client as Dropbox;

class Time {

    protected $dropbox;
    protected $digitWhizTimeService;
    protected $studentService;

    protected $originalFilename;
    protected $filename;

    protected $importDate;
    protected $importPath;

    public function __construct(DigitWhizTimeService $DigitWhizTimeService) {

        $this->digitWhizTimeService = $DigitWhizTimeService;
        $this->importDate = new \DateTime();
        $this->importPath = '/digitwhiz/import/time';
        $this->completedPath = '/digitwhiz/completed/time';
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
        $PLAYS = 'B';
        $POINTS = 'C';
        $TIME = 'D';

        foreach ($rowIterator as $row) {

            $cellIterator = $row->getCellIterator();
            $cellIterator->setIterateOnlyExistingCells(false); // Loop all cells, even if it is not set

            $a1Value = trim($objPHPExcel->getActiveSheet()->getCell('A'.$row->getRowIndex())->getValue());

            // CHECK FOR STUDENT DATA ROW
            if (!strlen($a1Value)) continue;
            if ($a1Value == 'Name') continue;

            $data = [];

            foreach ($cellIterator as $cell) {
                switch ($cell->getColumn()) {
                    case $STUDENT_NAME: $data['student_name'] = $cell->getValue(); break;
                    case $PLAYS: $data['plays'] = $cell->getValue(); break;
                    case $POINTS: $data['points'] = $cell->getValue(); break;
                    case $TIME: $data['dwtime'] = $cell->getValue(); break;
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

                $digitwhizTime = $this->digitWhizTimeService->create($data);

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