<?php

namespace Admin\Import\ThinkThroughMath;

use Admin\Student\Service as StudentService;
use Admin\ThinkThroughMath\Overview\Service as ThinkThroughMathOverviewService;
use \Dropbox\Client as Dropbox;

class Overview {

    protected $dropbox;
    protected $ttmOverviewService;
    protected $studentService;

    protected $originalFilename;
    protected $filename;

    protected $importDate;
    protected $importPath;

    public function __construct(ThinkThroughMathOverviewService $ttmOverviewService) {
        $this->ttmOverviewService = $ttmOverviewService;
        $this->importDate = new \DateTime();
        $this->importPath = '/thinkthroughmath/import/overview';
        $this->completedPath = '/thinkthroughmath/completed/overview';
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

        $STUDENT = 'A';
        $PLACEMENT_TEST_RESULT = 'B';
        $LESSONS_ATTEMPTED = 'C';
        $TOTAL_LESSONS_PASSED = 'D';
        $TARGET_LESSON_PASS_RATE = 'E';
        $PRECURSOR_LESSON_PASS_RATE = 'F';
        $LESSONS_PASSED_BY_PRE_QUIZ = 'G';
        $PRE_QUIZ_AVG = 'H';
        $POST_QUIZ_AVG = 'I';
        $PROBLEMS_ATTEMPTED = 'J';
        $EARNED_POINTS = 'K';
        $LEARNING_COACH_HELPS = 'L';
        $LIVE_HELPS = 'M';
        $TOTAL_MATH_TIME = 'N';
        $SCHOOL_TIME = 'P';
        $EVENING_WEEKEND_TIME = 'O';

        foreach ($rowIterator as $row) {

            $cellIterator = $row->getCellIterator();
            $cellIterator->setIterateOnlyExistingCells(false); // Loop all cells, even if it is not set

            if(1 == $row->getRowIndex ()) continue; //skip first row

            $data = [];

            foreach ($cellIterator as $cell) {
                switch ($cell->getColumn()) {
                    case $STUDENT: $data['student'] = trim($cell->getValue()); break;
                    case $PLACEMENT_TEST_RESULT: $data['placement_test_result'] = trim($cell->getValue()); break;
                    case $LESSONS_ATTEMPTED: $data['lessons_attempted'] = trim($cell->getValue()); break;
                    case $TOTAL_LESSONS_PASSED: $data['total_lessons_passed'] = trim($cell->getValue()); break;
                    case $TARGET_LESSON_PASS_RATE: $data['target_lesson_pass_rate'] = trim($cell->getValue()); break;
                    case $PRECURSOR_LESSON_PASS_RATE: $data['precursor_lesson_pass_rate'] = trim($cell->getValue()); break;
                    case $LESSONS_PASSED_BY_PRE_QUIZ: $data['lessons_passed_by_pre_quiz'] = trim($cell->getValue()); break;
                    case $PRE_QUIZ_AVG: $data['pre_quiz_avg'] = trim($cell->getValue()); break;
                    case $POST_QUIZ_AVG: $data['post_quiz_avg'] = trim($cell->getValue()); break;
                    case $PROBLEMS_ATTEMPTED: $data['problems_attempted'] = trim($cell->getValue()); break;
                    case $EARNED_POINTS: $data['earned_points'] = trim($cell->getValue()); break;
                    case $LEARNING_COACH_HELPS: $data['learning_coach_helps'] = trim($cell->getValue()); break;
                    case $LIVE_HELPS: $data['live_helps'] = trim($cell->getValue()); break;
                    case $TOTAL_MATH_TIME: $data['total_math_time'] = trim($cell->getValue()); break;
                    case $SCHOOL_TIME: $data['school_time'] = trim($cell->getValue()); break;
                    case $EVENING_WEEKEND_TIME: $data['evening_weekend_time'] = trim($cell->getValue()); break;
                }
            }

            // FIND STUDENT
            $student = null;

            $nameParts = explode(' ', $data['student']);
            $mname = $nameParts[count($nameParts)-1];

            if (strlen($mname))
                $student = $this->studentService->getWithStudentMname($mname);

            if ($student) {

                $data['import_filename'] = $this->originalFilename;
                $data['imported_at'] = $this->importDate->format('Y-m-d H:i:s');
                $data['student_id'] = $student->id;

                $ttmOverview = $this->ttmOverviewService->create($data);

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