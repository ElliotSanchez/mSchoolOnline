<?php

namespace Admin\Teacher;

use Admin\User\UserAbstract as UserAbstract;

class Entity extends UserAbstract {

    public $username;
    public $password;
    public $email;
    public $firstName;
    public $lastName;
    public $isSchoolAdmin;
    public $displayedWelcome;

    public $accountId;
    public $schoolId;

    public function getFullName() {
        return $this->firstName . ' ' . $this->lastName;
    }

    public function setPassword($password) {

        $bcrypt = new \Zend\Crypt\Password\Bcrypt();

        $this->password = $bcrypt->create($password);

    }

    public function setPasswordHash($hash) {
        $this->password = $hash;
    }

    public function create($data) {

        parent::create($data);
        $this->username = (!empty($data['username'])) ? $data['username'] : null;
        //$this->password = (!empty($data['password'])) ? $data['password'] : null;
        $this->email = (!empty($data['email'])) ? $data['email'] : null;
        $this->firstName = (!empty($data['first_name'])) ? $data['first_name'] : null;
        $this->lastName = (!empty($data['last_name'])) ? $data['last_name'] : null;
        $this->isSchoolAdmin = (!empty($data['is_school_admin'])) ? $data['is_school_admin'] : 0;
        $this->displayedWelcome = (!empty($data['displayed_welcome'])) ? $data['displayed_welcome'] : 0;

        $this->accountId = (!empty($data['account_id'])) ? $data['account_id'] : null;
        $this->schoolId = (!empty($data['school_id'])) ? $data['school_id'] : null;

        if(isset($data['password']))
            $this->setPassword($data['password']);

    }

    public function exchangeArray(array $data)
    {

        if (!$this->id) $this->id = (isset($data['id']) && !empty($data['id'])) ? $data['id'] : $this->id;

        $this->username = (!empty($data['username'])) ? $data['username'] : $this->username;
        $this->password = (!empty($data['password']) && strlen($data['password'])) ? $data['password'] : $this->password;
        $this->email = (!empty($data['email'])) ? $data['email'] : $this->email;
        $this->firstName = (!empty($data['first_name'])) ? $data['first_name'] : $this->firstName;
        $this->lastName = (!empty($data['last_name'])) ? $data['last_name'] : $this->lastName;
        $this->isSchoolAdmin = (!empty($data['is_school_admin'])) ? $data['is_school_admin'] : $this->isSchoolAdmin;
        $this->displayedWelcome = (!empty($data['displayed_welcome'])) ? $data['displayed_welcome'] : $this->displayedWelcome;

        $this->accountId = (!empty($data['account_id'])) ? $data['account_id'] : $this->accountId;
        $this->schoolId = (!empty($data['school_id'])) ? $data['school_id'] : $this->schoolId;

        $this->exchangeDates($data);
    }

    public function toData() {

        $data = array(
            'username' => $this->username,
            'email' => $this->email,
            'first_name' => $this->firstName,
            'last_name' => $this->lastName,
            'is_school_admin' => $this->isSchoolAdmin,
            'account_id' => $this->accountId,
            'school_id' => $this->schoolId,
            'displayed_welcome' => (int) $this->displayedWelcome,
        );

        if ($this->password)
            $data['password'] = $this->password;

        return $data;

    }

    public function getArrayCopy() {
        return $this->toData();
    }

}