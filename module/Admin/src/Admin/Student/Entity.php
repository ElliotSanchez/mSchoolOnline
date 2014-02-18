<?php

namespace Admin\Student;

use Admin\ModelAbstract\EntityAbstract;

class Entity extends EntityAbstract {

    public $number;
    public $username;
    public $password;
    public $email;
    public $firstName;
    public $lastName;
    public $dob;
    public $gender;
    public $ethnicity;
    public $iep;
    public $gradeLevel;

    public $accountId;
    public $schoolId;

    public function generatePassword() {
        // THIS IS MEANT TO BE A RELATIVELY FRIENDLY INITIAL PASSWORD FOR A STUDENT
        $this->password = substr(str_shuffle("abcdefghijklmnopqrstuvwxyz"), 0, 4) . substr(str_shuffle("0123456789"), 0, 4);
    }

    public function setPassword($password) {

        // THIS FUNCTION DOES NOT HAS THE PASSWORDS BY DESIGN.
        // THE INTENTION IS THAT ADMINS AND TEACHERS CAN PASSWORDS WHEN STUDENTS FORGET THEM.
        $this->password = $password;

//        $bcrypt = new \Zend\Crypt\Password\Bcrypt();
//
//        $this->password = $bcrypt->create($password);

    }

    public function create($data) {

        parent::create($data);
        $this->number = (!empty($data['number'])) ? $data['number'] : null;
        $this->username = (!empty($data['username'])) ? $data['username'] : null;
        $this->email = (!empty($data['email'])) ? $data['email'] : null;
        $this->firstName = (!empty($data['first_name'])) ? $data['first_name'] : null;
        $this->lastName = (!empty($data['last_name'])) ? $data['last_name'] : null;
        $this->dob = (!empty($data['dob'])) ? (new \DateTime($data['dob'])) : null;
        $this->gender = (!empty($data['gender'])) ? $data['gender'] : null;
        $this->ethnicity = (!empty($data['ethnicity'])) ? $data['ethnicity'] : null;
        $this->iep = (!empty($data['iep'])) ? $data['iep'] : null;
        $this->gradeLevel = (!empty($data['grade_level'])) ? $data['grade_level'] : null;

        $this->accountId = (!empty($data['account_id'])) ? $data['account_id'] : null;
        $this->schoolId = (!empty($data['school_id'])) ? $data['school_id'] : null;

        if(isset($data['password']))
            $this->setPassword($data['password']);

    }

    public function exchangeArray(array $data)
    {

        if (!$this->id) $this->id = (isset($data['id']) && !empty($data['id'])) ? $data['id'] : null;

        $this->number = (!empty($data['number'])) ? $data['number'] : null;
        $this->username = (!empty($data['username'])) ? $data['username'] : null;
        $this->password = (!empty($data['password']) && strlen($data['password'])) ? $data['password'] : null;
        $this->email = (!empty($data['email'])) ? $data['email'] : null;
        $this->firstName = (!empty($data['first_name'])) ? $data['first_name'] : null;
        $this->lastName = (!empty($data['last_name'])) ? $data['last_name'] : null;
        $this->dob = (!empty($data['dob'])) ? (new \DateTime($data['dob'])) : null;
        $this->gender = (!empty($data['gender'])) ? $data['gender'] : null;
        $this->ethnicity = (!empty($data['ethnicity'])) ? $data['ethnicity'] : null;
        $this->iep = (!empty($data['iep'])) ? $data['iep'] : null;
        $this->gradeLevel = (!empty($data['grade_level'])) ? $data['grade_level'] : null;

        $this->accountId = (!empty($data['account_id'])) ? $data['account_id'] : null;
        $this->schoolId = (!empty($data['school_id'])) ? $data['school_id'] : null;

        $this->exchangeDates($data);
    }

    public function toData() {

        $data = array(
            'number' => $this->number,
            'username' => $this->username,
            'email' => $this->email,
            'first_name' => $this->firstName,
            'last_name' => $this->lastName,
            'dob' => ($this->dob) ? ($this->dob->format('Y-m-d')) : (null),
            'gender' => $this->gender,
            'ethnicity' => $this->ethnicity,
            'iep' => $this->iep,
            'grade_level' => $this->gradeLevel,
            'account_id' => $this->accountId,
            'school_id' => $this->schoolId,
        );

        if ($this->password)
            $data['password'] = $this->password;

        return $data;

    }

    public function getArrayCopy() {
        return $this->toData();
    }

}