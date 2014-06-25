<?php

namespace Admin\Import\Dreambox;

use Admin\Student\Service as StudentService;
use Admin\Dreambox\Usage\Service as DreamboxUsageService;
use \Dropbox\Client as Dropbox;

class Usage {

    protected $dropbox;
    protected $dreamboxUsageService;
    protected $studentService;

    protected $originalFilename;
    protected $filename;

    protected $importDate;
    protected $importPath;
    protected $filenamePrefix;

    public function __construct(DreamboxUsageService $dreamboxUsageService) {
        $this->dreamboxUsageService = $dreamboxUsageService;
        $this->importDate = new \DateTime();
        $this->importPath = '/dreambox/import';
        $this->completedPath = '/dreambox/completed';
        $this->filenamePrefix = 'Dreambox_Usage';
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

            if (substr($filename, 0, strlen($this->filenamePrefix)) != $this->filenamePrefix)
                continue;

            echo "importing " . $filename . '<br>';

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

        $STUDENT_FIRST_NAME = 'A';
        $STUDENT_LAST_NAME = 'B';
        $STUDENT_ID = 'C';
        $GRADE = 'D';
        $TEACHER_EMAILS = 'E';
        $CLASS_NAME = 'F';
        $SCHOOL_NAME = 'G';
        $INTERVENTION = 'H';
        $SESSIONS = 'I';
        $TIME_ON_TASK = 'J';
        $LESSONS_COMPLETED = 'K';
        $UNIQUE_LESSONS_COMPLETED = 'L';
        $UNITS_COMPLETED = 'M';

        foreach ($rowIterator as $row) {

            $cellIterator = $row->getCellIterator();
            $cellIterator->setIterateOnlyExistingCells(false); // Loop all cells, even if it is not set

            if(1 == $row->getRowIndex ()) continue; //skip first row

            $data = [];

            foreach ($cellIterator as $cell) {
                switch ($cell->getColumn()) {
                    case $STUDENT_FIRST_NAME: $data['first_name'] = $cell->getValue(); break;
                    case $STUDENT_LAST_NAME: $data['last_name'] = $cell->getValue(); break;
                    case $STUDENT_ID: $data['student_id'] = $cell->getValue(); break;
                    case $GRADE: $data['student_grade'] = $cell->getValue(); break;
                    case $TEACHER_EMAILS: $data['teacher_emails'] = $cell->getValue(); break;
                    case $CLASS_NAME: $data['class_name'] = $cell->getValue(); break;
                    case $SCHOOL_NAME: $data['school_name'] = $cell->getValue(); break;
                    case $INTERVENTION: $data['intervention'] = $cell->getValue(); break;
                    case $SESSIONS: $data['sessions'] = $cell->getValue(); break;
                    case $TIME_ON_TASK: $data['time_on_task'] = $cell->getValue(); break;
                    case $LESSONS_COMPLETED: $data['lessons_completed'] = $cell->getValue(); break;
                    case $UNIQUE_LESSONS_COMPLETED: $data['unique_lessons_completed'] = $cell->getValue(); break;
                    case $UNITS_COMPLETED: $data['units_completed'] = $cell->getValue(); break;
                }
            }

            // FIND STUDENT
            $student = null;

            if (!empty($data['student_id']))
                $student = $this->studentService->getWithStudentNumber($data['student_id']);

            if ($student) {

                $data['import_filename'] = $this->originalFilename;
                $data['imported_at'] = $this->importDate->format('Y-m-d H:i:s');
                $data['student_id'] = $student->id;

                $dreamboxUsage = $this->dreamboxUsageService->create($data);

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