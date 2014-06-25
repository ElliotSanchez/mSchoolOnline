<?php

namespace Admin\Import;

use Admin\Student\Service as StudentService;
use Admin\Iready\Service as IreadyService;
use Admin\IreadyData\Service as IreadyDataService;
use \Dropbox\Client as Dropbox;

class Iready {

    protected $dropbox;
    protected $ireadyService;
    protected $ireadyDataService;
    protected $studentService;

    protected $originalFilename;
    protected $filename;

    public function __construct(IreadyService $ireadyService, IreadyDataService $ireadyDataService) {
        $this->ireadyService = $ireadyService;
        $this->ireadyDataService = $ireadyDataService;
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

        // THE FOLLOWING COLUMNS ARE VARIABLE
        $DIAGNOSTIC_NUMBER_AND_OPERATIONS_SCALE_SCORE_1 = 'N';
        $DIAGNOSTIC_NUMBER_AND_OPERATIONS_PLACEMENT_1 = 'O';
        $DIAGNOSTIC_ALGEBRA_AND_ALGEBRAIC_THINKING_SCALE_SCORE_1 = 'P';
        $DIAGNOSTIC_ALGEBRA_AND_ALGEBRAIC_THINKING_PLACEMENT_1 = 'Q';
        $DIAGNOSTIC_MEASUREMENT_AND_DATA_SCALE_SCORE_1 = 'R';
        $DIAGNOSTIC_MEASUREMENT_AND_DATA_PLACEMENT_1 = 'S';
        $DIAGNOSTIC_GEOMETRY_SCALE_SCORE_1 = 'T';
        $DIAGNOSTIC_GEOMETRY_PLACEMENT_1 = 'U';
        $DIAGNOSTIC_COMPLETION_DATE_2 = 'V';
        $DIAGNOSTIC_OVERALL_SCALE_SCORE_2 = 'W';
        $DIAGNOSTIC_OVERALL_PLACEMENT_2 = 'X';
        $DIAGNOSTIC_NOTES_2 = 'Y';
        $DIAGNOSTIC_NUMBER_AND_OPERATIONS_SCALE_SCORE_2 = 'Z';
        $DIAGNOSTIC_NUMBER_AND_OPERATIONS_PLACEMENT_2 = 'AA';
        $DIAGNOSTIC_ALGEBRA_AND_ALGEBRAIC_THINKING_SCALE_SCORE_2 = 'AB';
        $DIAGNOSTIC_ALGEBRA_AND_ALGEBRAIC_THINKING_PLACEMENT_2 = 'AC';
        $DIAGNOSTIC_MEASUREMENT_AND_DATA_SCALE_SCORE_2 = 'AD';
        $DIAGNOSTIC_MEASUREMENT_AND_DATA_PLACEMENT_2 = 'AE';
        $DIAGNOSTIC_GEOMETRY_SCALE_SCORE_2 = 'AF';
        $DIAGNOSTIC_GEOMETRY_PLACEMENT_2	 = 'AG';
        $DIAGNOSTIC_COMPLETION_DATE_3 = 'AH';
        $DIAGNOSTIC_OVERALL_SCALE_SCORE_3 = 'AI';
        $DIAGNOSTIC_OVERALL_PLACEMENT_3 = 'AJ';
        $DIAGNOSTIC_NOTES_3 = 'AK';
        $DIAGNOSTIC_NUMBER_AND_OPERATIONS_SCALE_SCORE_3 = 'AL';
        $DIAGNOSTIC_NUMBER_AND_OPERATIONS_PLACEMENT_3 = 'AM';
        $DIAGNOSTIC_ALGEBRA_AND_ALGEBRAIC_THINKING_SCALE_SCORE_3 = 'AN';
        $DIAGNOSTIC_ALGEBRA_AND_ALGEBRAIC_THINKING_PLACEMENT_3 = 'AO';
        $DIAGNOSTIC_MEASUREMENT_AND_DATA_SCALE_SCORE_3 = 'AP';
        $DIAGNOSTIC_MEASUREMENT_AND_DATA_PLACEMENT_3 = 'AQ';
        $DIAGNOSTIC_GEOMETRY_SCALE_SCORE_3 = 'AR';
        $DIAGNOSTIC_GEOMETRY_PLACEMENT_3 = 'AS';
        $DIAGNOSTIC_COMPLETION_DATE_MOST_RECENT = 'AT';
        $DIAGNOSTIC_OVERALL_SCALE_SCORE_MOST_RECENT = 'AU';
        $DIAGNOSTIC_OVERALL_PLACEMENT_MOST_RECENT = 'AV';
        $DIAGNOSTIC_NOTES_MOST_RECENT = 'AW';
        $DIAGNOSTIC_NUMBER_AND_OPERATIONS_SCALE_SCORE_MOST_RECENT = 'AX';
        $DIAGNOSTIC_NUMBER_AND_OPERATIONS_PLACEMENT_MOST_RECENT = 'AY';
        $DIAGNOSTIC_ALGEBRA_AND_ALGEBRAIC_THINKING_SCALE_SCORE_MOST_RECENT = 'AZ';
        $DIAGNOSTIC_ALGEBRA_AND_ALGEBRAIC_THINKING_PLACEMENT_MOST_RECENT = 'BA';
        $DIAGNOSTIC_MEASUREMENT_AND_DATA_SCALE_SCORE_MOST_RECENT = 'BB';
        $DIAGNOSTIC_MEASUREMENT_AND_DATA_PLACEMENT_MOST_RECENT = 'BC';
        $DIAGNOSTIC_GEOMETRY_SCALE_SCORE_MOST_RECENT = 'BD';
        $DIAGNOSTIC_GEOMETRY_PLACEMENT_MOST_RECENT = 'BE';

        foreach ($rowIterator as $row) {

            $cellIterator = $row->getCellIterator();
            $cellIterator->setIterateOnlyExistingCells(false); // Loop all cells, even if it is not set

            if(1 == $row->getRowIndex ()) continue; //skip first row

            $data = [];
            $diagnosticData = [];

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

                    case $DIAGNOSTIC_NUMBER_AND_OPERATIONS_SCALE_SCORE_1: $diagnosticData['diagnostic_number_and_operations_scale_score_1'] = trim($cell->getValue()); break;
                    case $DIAGNOSTIC_NUMBER_AND_OPERATIONS_PLACEMENT_1: $diagnosticData['diagnostic_number_and_operations_placement_1'] = trim($cell->getValue()); break;
                    case $DIAGNOSTIC_ALGEBRA_AND_ALGEBRAIC_THINKING_SCALE_SCORE_1: $diagnosticData['diagnostic_algebra_and_algebraic_thinking_scale_score_1'] = trim($cell->getValue()); break;
                    case $DIAGNOSTIC_ALGEBRA_AND_ALGEBRAIC_THINKING_PLACEMENT_1: $diagnosticData['diagnostic_algebra_and_algebraic_thinking_placement_1'] = trim($cell->getValue()); break;
                    case $DIAGNOSTIC_MEASUREMENT_AND_DATA_SCALE_SCORE_1: $diagnosticData['diagnostic_measurement_and_data_scale_score_1'] = trim($cell->getValue()); break;
                    case $DIAGNOSTIC_MEASUREMENT_AND_DATA_PLACEMENT_1: $diagnosticData['diagnostic_measurement_and_data_placement_1'] = trim($cell->getValue()); break;
                    case $DIAGNOSTIC_GEOMETRY_SCALE_SCORE_1: $diagnosticData['diagnostic_geometry_scale_score_1'] = trim($cell->getValue()); break;
                    case $DIAGNOSTIC_GEOMETRY_PLACEMENT_1: $diagnosticData['diagnostic_geometry_placement_1'] = trim($cell->getValue()); break;
                    case $DIAGNOSTIC_COMPLETION_DATE_2: $diagnosticData['diagnostic_completion_date_2'] = trim($cell->getValue()); break;
                    case $DIAGNOSTIC_OVERALL_SCALE_SCORE_2: $diagnosticData['diagnostic_overall_scale_score_2'] = trim($cell->getValue()); break;
                    case $DIAGNOSTIC_OVERALL_PLACEMENT_2: $diagnosticData['diagnostic_overall_placement_2'] = trim($cell->getValue()); break;
                    case $DIAGNOSTIC_NOTES_2: $diagnosticData['diagnostic_notes_2'] = trim($cell->getValue()); break;
                    case $DIAGNOSTIC_NUMBER_AND_OPERATIONS_SCALE_SCORE_2: $diagnosticData['diagnostic_number_and_operations_scale_score_2'] = trim($cell->getValue()); break;
                    case $DIAGNOSTIC_NUMBER_AND_OPERATIONS_PLACEMENT_2: $diagnosticData['diagnostic_number_and_operations_placement_2'] = trim($cell->getValue()); break;
                    case $DIAGNOSTIC_ALGEBRA_AND_ALGEBRAIC_THINKING_SCALE_SCORE_2: $diagnosticData['diagnostic_algebra_and_algebraic_thinking_scale_score_2'] = trim($cell->getValue()); break;
                    case $DIAGNOSTIC_ALGEBRA_AND_ALGEBRAIC_THINKING_PLACEMENT_2: $diagnosticData['diagnostic_algebra_and_algebraic_thinking_placement_2'] = trim($cell->getValue()); break;
                    case $DIAGNOSTIC_MEASUREMENT_AND_DATA_SCALE_SCORE_2: $diagnosticData['diagnostic_measurement_and_data_scale_score_2'] = trim($cell->getValue()); break;
                    case $DIAGNOSTIC_MEASUREMENT_AND_DATA_PLACEMENT_2: $diagnosticData['diagnostic_measurement_and_data_placement_2'] = trim($cell->getValue()); break;
                    case $DIAGNOSTIC_GEOMETRY_SCALE_SCORE_2: $diagnosticData['diagnostic_geometry_scale_score_2'] = trim($cell->getValue()); break;
                    case $DIAGNOSTIC_GEOMETRY_PLACEMENT_2: $diagnosticData['diagnostic_geometry_placement_2'] = trim($cell->getValue()); break;
                    case $DIAGNOSTIC_COMPLETION_DATE_3: $diagnosticData['diagnostic_completion_date_3'] = trim($cell->getValue()); break;
                    case $DIAGNOSTIC_OVERALL_SCALE_SCORE_3: $diagnosticData['diagnostic_overall_scale_score_3'] = trim($cell->getValue()); break;
                    case $DIAGNOSTIC_OVERALL_PLACEMENT_3: $diagnosticData['diagnostic_overall_placement_3'] = trim($cell->getValue()); break;
                    case $DIAGNOSTIC_NOTES_3: $diagnosticData['diagnostic_notes_3'] = trim($cell->getValue()); break;
                    case $DIAGNOSTIC_NUMBER_AND_OPERATIONS_SCALE_SCORE_3: $diagnosticData['diagnostic_number_and_operations_scale_score_3'] = trim($cell->getValue()); break;
                    case $DIAGNOSTIC_NUMBER_AND_OPERATIONS_PLACEMENT_3: $diagnosticData['diagnostic_number_and_operations_placement_3'] = trim($cell->getValue()); break;
                    case $DIAGNOSTIC_ALGEBRA_AND_ALGEBRAIC_THINKING_SCALE_SCORE_3: $diagnosticData['diagnostic_algebra_and_algebraic_thinking_scale_score_3'] = trim($cell->getValue()); break;
                    case $DIAGNOSTIC_ALGEBRA_AND_ALGEBRAIC_THINKING_PLACEMENT_3: $diagnosticData['diagnostic_algebra_and_algebraic_thinking_placement_3'] = trim($cell->getValue()); break;
                    case $DIAGNOSTIC_MEASUREMENT_AND_DATA_SCALE_SCORE_3: $diagnosticData['diagnostic_measurement_and_data_scale_score_3'] = trim($cell->getValue()); break;
                    case $DIAGNOSTIC_MEASUREMENT_AND_DATA_PLACEMENT_3: $diagnosticData['diagnostic_measurement_and_data_placement_3'] = trim($cell->getValue()); break;
                    case $DIAGNOSTIC_GEOMETRY_SCALE_SCORE_3: $diagnosticData['diagnostic_geometry_scale_score_3'] = trim($cell->getValue()); break;
                    case $DIAGNOSTIC_GEOMETRY_PLACEMENT_3: $diagnosticData['diagnostic_geometry_placement_3'] = trim($cell->getValue()); break;
                    case $DIAGNOSTIC_COMPLETION_DATE_MOST_RECENT: $diagnosticData['diagnostic_completion_date_most_recent'] = trim($cell->getValue()); break;
                    case $DIAGNOSTIC_OVERALL_SCALE_SCORE_MOST_RECENT: $diagnosticData['diagnostic_overall_scale_score_most_recent'] = trim($cell->getValue()); break;
                    case $DIAGNOSTIC_OVERALL_PLACEMENT_MOST_RECENT: $diagnosticData['diagnostic_overall_placement_most_recent'] = trim($cell->getValue()); break;
                    case $DIAGNOSTIC_NOTES_MOST_RECENT: $diagnosticData['diagnostic_notes_most_recent'] = trim($cell->getValue()); break;
                    case $DIAGNOSTIC_NUMBER_AND_OPERATIONS_SCALE_SCORE_MOST_RECENT: $diagnosticData['diagnostic_number_and_operations_scale_score_most_recent'] = trim($cell->getValue()); break;
                    case $DIAGNOSTIC_NUMBER_AND_OPERATIONS_PLACEMENT_MOST_RECENT: $diagnosticData['diagnostic_number_and_operations_placement_most_recent'] = trim($cell->getValue()); break;
                    case $DIAGNOSTIC_ALGEBRA_AND_ALGEBRAIC_THINKING_SCALE_SCORE_MOST_RECENT: $diagnosticData['diagnostic_algebra_and_algebraic_thinking_scale_score_most_recent'] = trim($cell->getValue()); break;
                    case $DIAGNOSTIC_ALGEBRA_AND_ALGEBRAIC_THINKING_PLACEMENT_MOST_RECENT: $diagnosticData['diagnostic_algebra_and_algebraic_thinking_placement_most_recent'] = trim($cell->getValue()); break;
                    case $DIAGNOSTIC_MEASUREMENT_AND_DATA_SCALE_SCORE_MOST_RECENT: $diagnosticData['diagnostic_measurement_and_data_scale_score_most_recent'] = trim($cell->getValue()); break;
                    case $DIAGNOSTIC_MEASUREMENT_AND_DATA_PLACEMENT_MOST_RECENT: $diagnosticData['diagnostic_measurement_and_data_placement_most_recent'] = trim($cell->getValue()); break;
                    case $DIAGNOSTIC_GEOMETRY_SCALE_SCORE_MOST_RECENT: $diagnosticData['diagnostic_geometry_scale_score_most_recent'] = trim($cell->getValue()); break;
                    case $DIAGNOSTIC_GEOMETRY_PLACEMENT_MOST_RECENT: $diagnosticData['diagnostic_geometry_placement_most_recent'] = trim($cell->getValue()); break;
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

                $iready = $this->ireadyService->create($data);

                foreach ($diagnosticData as $column => $value) {

                    if (trim($value) == '') continue;

                    $ireadyData = [
                        'iready_id' => $iready->id,
                        'column' => $column,
                        'value' => $value,
                    ];

                    $this->ireadyDataService->create($ireadyData);

                }


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