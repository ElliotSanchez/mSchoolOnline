<?php

namespace Admin\User;

use Admin\ModelAbstract\EntityAbstract;
use Zend\Authentication\Result as AuthResult;
use Admin\User\UserAbstract as UserAbstract;

class Entity extends UserAbstract {

    public $username;
    public $password;
    public $email;
    public $firstName;
    public $lastName;
    public $isSchoolAdmin;

    public $schoolId;

    public function create($data) {

        parent::create($data);
        $this->username = (!empty($data['username'])) ? $data['username'] : null;
        //$this->password = (!empty($data['password'])) ? $data['password'] : null;
        $this->email = (!empty($data['email'])) ? $data['email'] : null;
        $this->firstName = (!empty($data['first_name'])) ? $data['first_name'] : null;
        $this->lastName = (!empty($data['last_name'])) ? $data['last_name'] : null;
        $this->isSchoolAdmin = (!empty($data['is_school_admin'])) ? $data['is_school_admin'] : null;

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
        $this->isSchoolAdmin = (!empty($data['is_school_admin'])) ? $data['is_school_admin'] : 0;

        $this->schoolId = (!empty($data['school_id'])) ? $data['school_id'] : null;

        $this->exchangeDates($data);
    }

    public function toData() {

        $data = array(
            'username' => $this->username,
            'email' => $this->email,
            'first_name' => $this->firstName,
            'last_name' => $this->lastName,
        );

        if ($this->password)
            $data['password'] = $this->password;

        return $data;

    }

    public function getArrayCopy() {
        return $this->toData();
    }

    public function getFullName() {
        return $this->firstName . ' ' . $this->lastName;
    }

}