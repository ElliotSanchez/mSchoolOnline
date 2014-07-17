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

    protected $studentData;

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

            //$this->cleanUp();

        }

    }

    protected function importFile() {

        $importFileName = $this->filename;

        //echo $this->originalFilename . '<br>';

        $objPHPExcel = \PHPExcel_IOFactory::load($importFileName);

        // FIND STUDENT
        $STUDENT_NAME = 'A6';
        $studentNameCellValue = trim($objPHPExcel->getActiveSheet()->getCell($STUDENT_NAME)->getValue());
        $mname = '';
        $student = null;

        $studentNameTemp = trim(str_replace('Student:', '', $studentNameCellValue));
        $studentNameParts = explode(' ', $studentNameTemp);
        array_pop($studentNameParts); // REMOVE THE ID FROM THE END

        $mname = $studentNameParts[count($studentNameParts)-1]; // GET Mname FROM LAST ELEMENT

        if (strlen($mname)) {
            $student = $this->studentService->getWithStudentMname($mname);
        }

        if (!$student) return; // NO STUDENT FOUND

        // GET REMAINING DATA
        $SCHOOL_NAME = 'A2';
        $TEACHER_NAME = 'A3';
        $STUDENT_GRADE = 'A4';
        $STUDENT_CLASS = 'A5';
        $FILE_DATE = 'A7';
        $FIRST_LOGIN = 'A9';
        $LAST_LOGIN = 'A10';

        $this->studentData = array(
            'school_name' => trim($objPHPExcel->getActiveSheet()->getCell($SCHOOL_NAME)->getValue()),
            'teacher_name' => trim($objPHPExcel->getActiveSheet()->getCell($TEACHER_NAME)->getValue()),
            'student_grade' => trim($objPHPExcel->getActiveSheet()->getCell($STUDENT_GRADE)->getValue()),
            'student_class' => trim($objPHPExcel->getActiveSheet()->getCell($STUDENT_CLASS)->getValue()),
            'file_date' => trim($objPHPExcel->getActiveSheet()->getCell($FILE_DATE)->getValue()),
            'first_login' => trim($objPHPExcel->getActiveSheet()->getCell($FIRST_LOGIN)->getValue()),
            'last_login' => trim($objPHPExcel->getActiveSheet()->getCell($LAST_LOGIN)->getValue()),
        );

        $data['import_filename'] = $this->originalFilename;
        $data['imported_at'] = $this->importDate->format('Y-m-d H:i:s');
        $data['student_id'] = $student->id;

        //$stMathStudent = $this->stMathStudentService->create($data);

        // PROCESS DATA CLUSTERS
        $postOverallLineNumber = $this->processOveralls($objPHPExcel);

        $this->processClustersStartingAt($objPHPExcel, $postOverallLineNumber+1);

        echo '<hr>';

        //$endOfCluster = $this->processClusterAt($clusterStart);

        //$rowIterator = $objPHPExcel->getActiveSheet()->getRowIterator();

    }

    protected function cleanUp() {

        $importCompletedPath = $this->completedPath . '/' .$this->importDate->format('Ymd_His');

        $metadata = $this->dropbox->getMetadata($importCompletedPath);

        if ($metadata == null) {
            $this->dropbox->createFolder($importCompletedPath);
        }

        $this->dropbox->move($this->originalFilename, $importCompletedPath . '/' . basename($this->originalFilename));

    }

    private function processOveralls(\PHPExcel $objPHPExcel) {

        $rowIterator = $objPHPExcel->getActiveSheet()->getRowIterator();

        $overallStartLine = null;

        foreach ($rowIterator as $row) {

            $aValue = trim($objPHPExcel->getActiveSheet()->getCell('A'.$row->getRowIndex())->getValue());

            if (substr_count($aValue,'Overall')) {
                //echo 'overall starts at ' . $clusterStart . '<br>';
                //echo $row->getRowIndex() . ': ' . $aValue . '<br>';
                $overallStartLine = $row->getRowIndex();
                break;
            }
        }

        if (!$overallStartLine) return null;

        $firstOverall = $objPHPExcel->getActiveSheet()->getCell('A'.($overallStartLine));
        $nextCellDown = $objPHPExcel->getActiveSheet()->getCell('A'.($overallStartLine+1));

        $postOverallLineNumber = null;

        if (substr_count($nextCellDown->getValue(),'Overall')) {
            // HAS SECOND OVERALL
            $postOverallLineNumber = $nextCellDown->getRow() + 1;
            //echo $firstOverall->getValue() . '<br>';
            //echo $nextCellDown->getValue() . '<br>';
        } else if (trim($nextCellDown->getValue()) == '') {
            // ONLY ONE OVERALL
            $postOverallLineNumber = $nextCellDown->getRow();
            //echo $firstOverall->getValue() . '<br>';
        }

        return $postOverallLineNumber;

    }

    private function processClustersStartingAt(\PHPExcel $objPHPExcel, $startingLine) {

        //echo 'processing clusters at: ' . $startingLine . '<br>';

        $aValue = trim($objPHPExcel->getActiveSheet()->getCell('A'.$startingLine)->getValue());

        if (trim($aValue) != '') {
            $finishLine = $this->processClusterAt($objPHPExcel, $startingLine);
            while ($finishLine) {
                $finishLine = $this->processClusterAt($objPHPExcel, $finishLine+2);
            }
        }

        //$aValue = trim($objPHPExcel->getActiveSheet()->getCell('A'.$row->getRowIndex())->getValue());

    }

    private function processClusterAt(\PHPExcel $objPHPExcel, $startingLine) {

        //$DIAGNOSTIC_SCORE_CELL = 'A'.($startingLine+3);

        $TOPIC_CELL = 'A'.($startingLine);
        $MASTERY_GOAL_CELL = 'A'.($startingLine+1);
        $CURRENT_MASTERY_CELL = 'A'.($startingLine+2);

        $topic = $objPHPExcel->getActiveSheet()->getCell($TOPIC_CELL)->getValue();
        $masteryGoalCell = trim(str_replace('Mastery Goal:', '', $objPHPExcel->getActiveSheet()->getCell($MASTERY_GOAL_CELL)->getValue()));
        //$diagnosticScoreCell = $objPHPExcel->getActiveSheet()->getCell($DIAGNOSTIC_SCORE_CELL)->getValue();
        $currentMasteryCell = trim(str_replace('Current Mastery:', '', $objPHPExcel->getActiveSheet()->getCell($CURRENT_MASTERY_CELL)->getValue()));

        $mainValues = array(
            'topic' => $topic,
            'mastery_goal' => $masteryGoalCell,
            'current_mastery' => $currentMasteryCell,
        );

        $objectivesStart = $startingLine + 4;

        $objectiveRowIterator = $objPHPExcel->getActiveSheet()->getRowIterator();

        $objectiveRowIterator->seek($objectivesStart);

        $currObjective = $objPHPExcel->getActiveSheet()->getCell('A'.$objectiveRowIterator->current()->getRowIndex());

        $currObjectiveValue = trim($currObjective->getValue());

        $hasData = false;

        $objectives = array();

        while(strlen($currObjectiveValue) && !substr_count($currObjectiveValue,$topic)) {

            $hasData = true;

            $rowIndex = $objectiveRowIterator->current()->getRowIndex();

            $objectives[] = array_merge($mainValues, array(
                'objective'     => $currObjectiveValue,
                'progress' 		=> $objPHPExcel->getActiveSheet()->getCell('B'. $rowIndex)->getValue(),
                'sessions_used' => $objPHPExcel->getActiveSheet()->getCell('C'.$rowIndex)->getValue(),
                'pre_quiz' 		=> $objPHPExcel->getActiveSheet()->getCell('D'.$rowIndex)->getValue(),
                'post_quiz' 	=> $objPHPExcel->getActiveSheet()->getCell('E'.$rowIndex)->getValue(),
            ));

            // MOVE TO NEXT
            $objectiveRowIterator->next();
            if (!$objectiveRowIterator->valid()) break;
            $currObjective = $objPHPExcel->getActiveSheet()->getCell('A'.$objectiveRowIterator->current()->getRowIndex());
            $currObjectiveValue = $currObjective->getValue();

        }

        if (!$hasData) return false;

        // GET OBJECTIVE GROWTH
        $currObjectiveGrowth = $objPHPExcel->getActiveSheet()->getCell('A'.$objectiveRowIterator->current()->getRowIndex())->getValue();
        $growthParts = explode(':', $currObjectiveGrowth);
        if (count($growthParts) > 1) {
            $objectiveGrowth = trim($growthParts[1]);
        } else {
            $objectiveGrowth = null;
        }

        foreach ($objectives as &$currObjectiveData) {
            $currObjectiveData['objective_growth'] = $objectiveGrowth;
            print_r($currObjectiveData);
        }

        return $objectiveRowIterator->current()->getRowIndex();

    }

}

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