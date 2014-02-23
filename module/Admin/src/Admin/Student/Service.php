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

    public function getWithStudentNumber($number) {

        return $this->table->fetchWith(array('number' => $number))->current();

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

        $NUMBER_COL = 'A';
        $FIRST_NAME_COL = 'B';
        $LAST_NAME_COL = 'C';
        $DOB_COL = 'D';
        $GENDER_COL = 'E';
        $GRADE_LEVEL_COL = 'F';
        $ETHNICITY_COL = 'G';
        $IEP_COL = 'H';

        foreach ($rowIterator as $row) {

            $cellIterator = $row->getCellIterator();
            $cellIterator->setIterateOnlyExistingCells(false); // Loop all cells, even if it is not set

            if(1 == $row->getRowIndex ()) continue; //skip first row

            $data = array();

            $data['account_id'] = $account->id;
            $data['school_id'] = $school->id;

            foreach ($cellIterator as $cell) {

                switch ($cell->getColumn()) {
                    case $NUMBER_COL:       $data['number'] = $cell->getValue();
                        break;
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
            $student->username = $this->generateUsernameFor($student);

            $student->generatePassword();
            $this->save($student);
        }

    }

    public function generateUsernameFor(Student $student) {
        $username =  strtolower($student->firstName.$student->lastName);

        $username = str_replace(' ', '', $username);

        if ($this->usernameExists($username)) {
            $username .= $student->number;
        }

        return $username;

    }

    public function usernameExists($username) {
        return count($this->table->fetchWith(array('username' => $username)));
    }

}