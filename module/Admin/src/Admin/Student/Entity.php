<?php

namespace Admin\Student;

use Admin\ModelAbstract\EntityAbstract;
use Admin\User\UserAbstract as UserAbstract;
use Zend\Crypt\BlockCipher;
use Admin\Module as AdminModule;

class Entity extends UserAbstract {

    public $number;
    public $mname;
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

    public function getFullName() {
        return $this->firstName . ' ' . $this->lastName;
    }

    public function generatePassword() {
        // THIS IS MEANT TO BE A RELATIVELY FRIENDLY INITIAL PASSWORD FOR A STUDENT
        $clearTextPassword = substr(str_shuffle("abcdefghijklmnopqrstuvwxyz"), 0, 4) . substr(str_shuffle("0123456789"), 0, 4);
        $this->setPassword($clearTextPassword);
    }

    public function setPassword($password) {

        // THIS FUNCTION DOES NOT HASH THE PASSWORDS BY DESIGN.
        // THE INTENTION IS THAT ADMINS AND TEACHERS CAN SEE PASSWORDS WHEN STUDENTS FORGET THEM.

        $cipher = BlockCipher::factory('mcrypt',
            array('algorithm' => 'aes')
        );

        $cipher->setKey(AdminModule::$studentPasswordKey);

        try {
            $encrypted = $cipher->encrypt($password);
            $this->password = $encrypted;
        } catch (\Exception $e) {

        }

    }

    public function validatePassword($password) {

        $cipher = BlockCipher::factory('mcrypt',
            array('algorithm' => 'aes')
        );

        $cipher->setKey(AdminModule::$studentPasswordKey);

        try {
            $decrypted = $cipher->decrypt($this->password);
        } catch (\Exception $e) {

        }

        return $password == $decrypted;
    }

    public function getUnencryptedPassword() {

        $cipher = BlockCipher::factory('mcrypt',
            array('algorithm' => 'aes')
        );

        $cipher->setKey(AdminModule::$studentPasswordKey);

        try {
            return $cipher->decrypt($this->password);
        } catch (\Exception $e) {

        }

        return null;

    }

    public function create($data) {

        parent::create($data);
        $this->number = (!empty($data['number'])) ? $data['number'] : null;
        $this->mname = (!empty($data['mname'])) ? $data['mname'] : null;
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
        $this->mname = (!empty($data['mname'])) ? $data['mname'] : $this->mname;
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
            'mname' => $this->mname,
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