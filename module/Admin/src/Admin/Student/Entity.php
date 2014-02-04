<?php

namespace Admin\Student;

use Admin\ModelAbstract\EntityAbstract;

class Entity extends EntityAbstract {

    public $username;
    public $password;
    public $email;
    public $firstName;
    public $lastName;

    public $accountId;
    public $schoolId;

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
        $this->username = (!empty($data['username'])) ? $data['username'] : null;
        $this->email = (!empty($data['email'])) ? $data['email'] : null;
        $this->firstName = (!empty($data['first_name'])) ? $data['first_name'] : null;
        $this->lastName = (!empty($data['last_name'])) ? $data['last_name'] : null;

        $this->accountId = (!empty($data['account_id'])) ? $data['account_id'] : null;
        $this->schoolId = (!empty($data['school_id'])) ? $data['school_id'] : null;

        if(isset($data['password']))
            $this->setPassword($data['password']);

    }

    public function exchangeArray(array $data)
    {

        if (!$this->id) $this->id = (isset($data['id']) && !empty($data['id'])) ? $data['id'] : null;

        $this->username = (!empty($data['username'])) ? $data['username'] : null;
        $this->password = (!empty($data['password']) && strlen($data['password'])) ? $data['password'] : null;
        $this->email = (!empty($data['email'])) ? $data['email'] : null;
        $this->firstName = (!empty($data['first_name'])) ? $data['first_name'] : null;
        $this->lastName = (!empty($data['last_name'])) ? $data['last_name'] : null;

        $this->accountId = (!empty($data['account_id'])) ? $data['account_id'] : null;
        $this->schoolId = (!empty($data['school_id'])) ? $data['school_id'] : null;

        $this->exchangeDates($data);
    }

    public function toData() {

        $data = array(
            'username' => $this->username,
            'email' => $this->email,
            'first_name' => $this->firstName,
            'last_name' => $this->lastName,
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