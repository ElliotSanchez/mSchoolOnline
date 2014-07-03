<?php

namespace Admin\Import\STMath;

use Admin\Student\Service as StudentService;
use Admin\STMath\Student\Service as STMathStudentService;
use \Dropbox\Client as Dropbox;

class Student {

    protected $dropbox;
    protected $stMathStudentService;
    protected $studentService;

    protected $originalFilename;
    protected $filename;

    protected $importDate;
    protected $importPath;

    public function __construct(STMathStudentService $STMathStudentService) {
        $this->stMathStudentService = $STMathStudentService;
        $this->importDate = new \DateTime();
        $this->importPath = '/stmath/import/student';
        $this->completedPath = '/stmath/completed/student';
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

        // FIND STUDENT
        $STUDENT_NAME = 'A6';
        $studentNameCellValue = trim($objPHPExcel->getActiveSheet()->getCell($STUDENT_NAME)->getValue());
        $mname = '';
        $student = null;

        $studentNameTemp = trim(str_replace('Student:', '', $studentNameCellValue));
        $studentNameParts = explode(' ', $studentNameTemp);
        array_pop($studentNameParts); // REMOVE THE ID FROM THE END
        //print_r($studentNameParts);

        $mname = $studentNameParts[count($studentNameParts)-1]; // GET Mname FROM LAST ELEMENT
        echo 'mname: ' . $mname . '<br>';
        if (strlen($mname)) {
            $student = $this->studentService->getWithStudentMname($mname);
        }

        if (!$student) return; // NO STUDENT FOUND


        $data['import_filename'] = $this->originalFilename;
        $data['imported_at'] = $this->importDate->format('Y-m-d H:i:s');
        $data['student_id'] = $student->id;

        $stMathStudent = $this->stMathStudentService->create($data);

//        foreach ($rowIterator as $row) {
//
//            $cellIterator = $row->getCellIterator();
//            $cellIterator->setIterateOnlyExistingCells(false); // Loop all cells, even if it is not set
//
//            $a1Value = trim($objPHPExcel->getActiveSheet()->getCell('A'.$row->getRowIndex())->getValue());
//
//            // CHECK FOR STUDENT DATA ROW
//            if ($row->getRowIndex() <= 7) continue; // DATA IS AFTER LINE 7
//            if ($a1Value == 'CLASS AVERAGE') continue;
//
//            $data = [];
//
//            foreach ($cellIterator as $cell) {
//                switch ($cell->getColumn()) {
//                    case $SCD: $data['scd'] = trim($cell->getValue()); break;
//                    case $FIRST_NAME: $data['first_name'] = trim($cell->getValue()); break;
//                    case $LAST_NAME: $data['last_name'] = trim($cell->getValue()); break;
//                    case $SCHOOL_SESSIONS: $data['school_sessions'] = trim($cell->getValue()); break;
//                    case $HOME_SESSIONS: $data['home_sessions'] = trim($cell->getValue()); break;
//                    case $SYLLABUS_STUDENT: $data['syllabus_student'] = trim($cell->getValue()); break;
//                    case $OBJECTIVES_ATTEMPTED: $data['objectives_attempted'] = trim($cell->getValue()); break;
//                    case $STANDARDS_MASTERY: $data['standards_mastery'] = trim($cell->getValue()); break;
//                    case $STANDARDS_ATTEMPTED: $data['standards_attempted'] = trim($cell->getValue()); break;
//                    case $CURRENT_OBJECTIVE: $data['current_objective'] = trim($cell->getValue()); break;
//                    case $MODULE: $data['module'] = trim($cell->getValue()); break;
//                    case $GAME: $data['game'] = trim($cell->getValue()); break;
//                    case $LEVEL: $data['level'] = trim($cell->getValue()); break;
//                    case $TOTAL_ATTEMPTS: $data['total_attempts'] = trim($cell->getValue()); break;
//                    case $LAST_SESSION: $data['last_session'] = trim($cell->getValue()); break;
//                    case $ALERTS: $data['alerts'] = trim($cell->getValue()); break;
//                    case $EXTRA_PLAYS: $data['extra_plays'] = trim($cell->getValue()); break;
//                    case $LOW_POST_QUIZ_SCORE: $data['low_post_quiz_score'] = trim($cell->getValue()); break;
//                    case $DECREASING_QUIZ_SCORE: $data['decreasing_quiz_score'] = trim($cell->getValue()); break;
//                    case $LOW_STANDARDS_MASTERY: $data['low_standards_mastery'] = trim($cell->getValue()); break;
//                    case $LOW_TIME_ON_TASK: $data['low_time_on_task'] = trim($cell->getValue()); break;
//                    case $HIGH_NUMBER_OF_TRIES: $data['high_number_of_tries'] = trim($cell->getValue()); break;
//                    case $LOW_STUDENT: $data['low_student'] = trim($cell->getValue()); break;
//                    case $LOW_ATTENDANCE: $data['low_attendance'] = trim($cell->getValue()); break;
//                    case $LEVEL_CANCELLING: $data['level_cancelling'] = trim($cell->getValue()); break;
//                    case $UNADDRESSED_HAND_RAISING: $data['unaddressed_hand_raising'] = trim($cell->getValue()); break;
//                    case $STUCK_ON_INTRO_OBJECTIVES: $data['stuck_on_intro_objectives'] = trim($cell->getValue()); break;
//                }
//            }
//
//            // FIND STUDENT
//            $student = null;
//
//            if (strlen($data['last_name']))
//                $student = $this->studentService->getWithStudentMname($data['last_name']);
//
//            if ($student) {
//
//                $data['import_filename'] = $this->originalFilename;
//                $data['imported_at'] = $this->importDate->format('Y-m-d H:i:s');
//                $data['student_id'] = $student->id;
//
//                $stMathStudent = $this->stMathStudentService->create($data);
//
//            } else {
//                // TODO NOT HANDLING MISSING STUDENT
//            }
//
//        }

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