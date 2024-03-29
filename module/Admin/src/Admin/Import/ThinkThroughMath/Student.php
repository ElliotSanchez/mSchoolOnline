<?php

namespace Admin\Import\ThinkThroughMath;

use Admin\Student\Service as StudentService;
use Admin\ThinkThroughMath\Student\Service as ThinkThroughMathStudentService;
use \Dropbox\Client as Dropbox;

class Student {

    protected $dropbox;
    protected $ttmStudentService;
    protected $studentService;

    protected $originalFilename;
    protected $filename;

    protected $downloadDate;

    protected $importDate;
    protected $importPath;

    public function __construct(ThinkThroughMathStudentService $ttmStudentService) {
        $this->ttmStudentService = $ttmStudentService;
        $this->importDate = new \DateTime();
        $this->importPath = '/thinkthroughmath/import/student';
        $this->completedPath = '/thinkthroughmath/completed/student';
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

            $this->importFile($filename);

            $this->cleanUp();

        }

    }

    protected function importFile($filename) {

        // FIND STUDENT FROM FILENAME
        $student = null;

        $filenameParts = explode('_', $filename);
        $nameParts = explode(' ', $filenameParts[0]);
        $mname = $nameParts[count($nameParts)-1];

        if (strlen($mname))
            $student = $this->studentService->getWithStudentMname($mname);

        // READ FILE
        $importFileName = $this->filename;

        $objPHPExcel = \PHPExcel_IOFactory::load($importFileName);

        $rowIterator = $objPHPExcel->getActiveSheet()->getRowIterator();

//        ORIGINAL FILE FORMAT FIELDS
//        $STUDENT_NAME = 'A';
//        $GRADE_NAME = 'B';
//        $CLASSROOM_NAME = 'C';
//        $PATHWAY_NAME = 'D';
//        $GRADE_LEVEL_DEVIATION = 'E';
//        $LESSON_NAME = 'F';
//        $TYPE = 'G';
//        $TESTED_OUT = 'H';
//        $PASSED = 'I';
//        $PRE_QUIZ_SCORE = 'J';
//        $POST_QUIZ_SCORE = 'K';
//        $TIME_ON_SYSTEM = 'L';
//        $DATE_STARTED = 'M';
//        $DATE_COMPLETED = 'N';
//                    case $STUDENT_NAME: $data['student_name'] = trim($cell->getValue()); break;
//                    case $CLASSROOM_NAME: $data['classroom_name'] = trim($cell->getValue()); break;
//                    case $PATHWAY_NAME: $data['pathway_name'] = trim($cell->getValue()); break;
//                    case $GRADE_LEVEL_DEVIATION: $data['grade_level_deviation'] = trim($cell->getValue()); break;
//                    case $DATE_STARTED: $data['date_started'] = trim($cell->getValue()); break;
//                    case $TYPE: $data['type'] = trim($cell->getValue()); break;
//                    case $TESTED_OUT: $data['tested_out'] = trim($cell->getValue()); break;
//                    case $PASSED: $data['passed'] = trim($cell->getValue()); break;
//                    case $PRE_QUIZ_SCORE: $data['pre_quiz_score'] = trim($cell->getValue()); break;

        $LESSON_NAME = 'A';
        $GRADE_NAME = 'B';
        $POST_QUIZ_SCORE = 'C';
        $TIME_ON_SYSTEM = 'D';
        $DATE_COMPLETED = 'E';

        foreach ($rowIterator as $row) {

            $cellIterator = $row->getCellIterator();
            $cellIterator->setIterateOnlyExistingCells(false); // Loop all cells, even if it is not set

            if(1 == $row->getRowIndex ()) continue; //skip first row

            $data = [];

            foreach ($cellIterator as $cell) {
                switch ($cell->getColumn()) {
                    case $GRADE_NAME: $data['grade_name'] = trim($cell->getValue()); break;
                    case $LESSON_NAME: $data['lesson_name'] = trim($cell->getValue()); break;
                    case $POST_QUIZ_SCORE: $data['post_quiz_score'] = trim($cell->getValue()); break;
                    case $TIME_ON_SYSTEM: $data['time_on_system'] = trim($cell->getValue()); break;
                    case $DATE_COMPLETED: $data['date_completed'] = trim($cell->getValue()); break;
                }
            }

            if ($student) {

                $data['import_filename'] = $this->originalFilename;
                $data['imported_at'] = $this->importDate->format('Y-m-d H:i:s');
                $data['download_date'] = $this->downloadDate->format('Y-m-d');
                $data['student_id'] = $student->id;

                $ttmStudent = $this->ttmStudentService->create($data);

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

        // EX. FIRSTNAME LASTNAME__2014-07-01_10_33_38.csv

        $temp = basename($this->originalFilename);

        $matches = array();
        preg_match('/(\d{4})-(\d{2})-(\d{2})/', $temp, $matches);

        if (count($matches)) {
            $dateString = $matches[0];
            $this->downloadDate = new \DateTime($dateString);
        }

    }

}