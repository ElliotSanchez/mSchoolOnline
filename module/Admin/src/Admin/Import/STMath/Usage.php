<?php

namespace Admin\Import\STMath;

use Admin\Student\Service as StudentService;
use Admin\STMath\Usage\Service as STMathUsageService;
use \Dropbox\Client as Dropbox;

class Usage {

    protected $dropbox;
    protected $stMathUsageService;
    protected $studentService;

    protected $originalFilename;
    protected $filename;

    protected $downloadDate;

    protected $importDate;
    protected $importPath;

    public function __construct(STMathUsageService $STMathUsageService) {
        $this->stMathUsageService = $STMathUsageService;
        $this->importDate = new \DateTime();
        $this->importPath = '/stmath/import/usage';
        $this->completedPath = '/stmath/completed/usage';
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

            $this->setDownloadDate();

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

        $SCD = 'A';
        $FIRST_NAME = 'B';
        $LAST_NAME = 'C';
        $AVG_TIME_WEEK = 'D';
        $AVG_PROGRESS_WEEK = 'E';
        $TOTAL_TIME = 'F';
        $SYLLABUS_PROGRESS = 'G';
        $FIRST_LOGIN = 'H';

        foreach ($rowIterator as $row) {

            $cellIterator = $row->getCellIterator();
            $cellIterator->setIterateOnlyExistingCells(false); // Loop all cells, even if it is not set

            $a1Value = trim($objPHPExcel->getActiveSheet()->getCell('A'.$row->getRowIndex())->getValue());

            // CHECK FOR STUDENT DATA ROW
            if ($row->getRowIndex() <= 7) continue; // DATA IS AFTER LINE 7
            if ($a1Value == 'CLASS AVERAGE') continue;

            $data = [];

            foreach ($cellIterator as $cell) {
                switch ($cell->getColumn()) {
                    case $SCD: $data['scd'] = trim($cell->getValue()); break;
                    case $FIRST_NAME: $data['first_name'] = trim($cell->getValue()); break;
                    case $LAST_NAME: $data['last_name'] = trim($cell->getValue()); break;
                    case $AVG_TIME_WEEK: $data['avg_time_week'] = trim($cell->getValue()); break;
                    case $AVG_PROGRESS_WEEK: $data['avg_progress_week'] = trim($cell->getValue()); break;
                    case $TOTAL_TIME: $data['total_time'] = trim($cell->getValue()); break;
                    case $SYLLABUS_PROGRESS: $data['syllabus_progress'] = trim($cell->getValue()); break;
                    case $FIRST_LOGIN: $data['first_login'] = trim($cell->getValue()); break;
                }
            }

            // FIND STUDENT
            $student = null;

            if (strlen($data['last_name']))
                $student = $this->studentService->getWithStudentMname($data['last_name']);

            if ($student) {

                $data['import_filename'] = $this->originalFilename;
                $data['imported_at'] = $this->importDate->format('Y-m-d H:i:s');
                $data['download_date'] = $this->downloadDate->format('Y-m-d');
                $data['student_id'] = $student->id;

                $stMathUsage = $this->stMathUsageService->create($data);

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

    private function setDownloadDate() {

        // EX. usageReport_26_6_2014_11_0_34.csv

        $temp = basename($this->originalFilename);

        $temp = str_replace('usageReport_', '', $temp);
        $temp = str_replace('.csv', '', $temp);
        $parts = explode('_', $temp);

        if (count($parts)) {
            $dateString = $parts[2] . '-' . $parts[1] . '-' . $parts[0];
            $this->downloadDate = new \DateTime($dateString);
        }

    }

}