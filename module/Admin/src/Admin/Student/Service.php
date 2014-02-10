<?php

namespace Admin\Student;

use Admin\ModelAbstract\ServiceAbstract as ServiceAbstract;
use Admin\Student\Entity as Student;
use Admin\Account\Entity as Account;
use Admin\School\Entity as School;

class Service extends ServiceAbstract {

    public function create($data) {

        $student = new Student();

        $student->create($data);

        $student = $this->table->save($student);

        return $student;

    }

    public function getForAccount(Account $account) {

        return $this->table->fetchWith(array('account_id' => $account->id));

    }

    public function getForSchool(School $school) {

        return $this->table->fetchWith(array('school_id' => $school->id));

    }

    public function importStudentsFromFile($filename, $account, $school) {

        $objPHPExcel = \PHPExcel_IOFactory::load($filename);

        $rowIterator = $objPHPExcel->getActiveSheet()->getRowIterator();

        $FIRST_NAME_COL = 'A';
        $LAST_NAME_COL = 'B';
        $DOB_COL = 'C';
        $GENDER_COL = 'D';
        $GRADE_LEVEL_COL = 'E';
        $ETHNICITY_COL = 'F';
        $IEP_COL = 'G';

        foreach ($rowIterator as $row) {

            $cellIterator = $row->getCellIterator();
            $cellIterator->setIterateOnlyExistingCells(false); // Loop all cells, even if it is not set

            if(1 == $row->getRowIndex ()) continue; //skip first row

            $data = array();

            $data['account_id'] = $account->id;
            $data['school_id'] = $school->id;

            foreach ($cellIterator as $cell) {

                switch ($cell->getColumn()) {
                    case $FIRST_NAME_COL:   $data['first_name'] = $cell->getValue();
                                            break;
                    case $LAST_NAME_COL:    $data['last_name'] = $cell->getValue();
                                            break;
                    case $DOB_COL:          $data['dob'] = $cell->getValue();
                                            break;
                    case $GENDER_COL:       $data['gender'] = $cell->getValue();
                                            break;
                    case $GRADE_LEVEL_COL:  $data['grade_level'] = $cell->getValue();
                                            break;
                    case $ETHNICITY_COL:    $data['ethnicity'] = $cell->getValue();
                                            break;
                    case $IEP_COL:          $data['iep'] = $cell->getValue();
                                            break;
                    default:                break;
                }

            }

            $student = $this->create($data);
            $student->username = $this->generateUserFor($student);
            $student->generatePassword();
            $this->save($student);
        }

    }

    public function generateUserFor(Student $student) {
        return strtolower(substr($student->firstName, 0, 1).$student->lastName);
    }

}