<?php

namespace Admin\CoachSignup;

use Admin\User\UserAbstract as UserAbstract;

class Entity extends UserAbstract {

    public $username;
    public $password;
    public $email;
    public $firstName;
    public $lastName;
    public $schoolName;
    public $schoolZipCode;
    public $role;
    public $confirmationKey;
    public $isConfirmed;
    public $createdAt;
    public $updatedAt;

    public function getFullName() {
        return $this->firstName . ' ' . $this->lastName;
    }

    public function setPassword($password) {

        $bcrypt = new \Zend\Crypt\Password\Bcrypt();

        $this->password = $bcrypt->create($password);

    }

    public function generateConfirmationKey() {

        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randomString = '';

        for ($i = 0; $i < 200; $i++) {
            str_shuffle($characters);
            $randomString .= $characters[rand(0, strlen($characters) - 1)];
        }

        $this->confirmationKey = $randomString;

    }

    public function create($data) {

        parent::create($data);
        $this->username = (!empty($data['username'])) ? $data['username'] : null;
        $this->password = (!empty($data['password'])) ? $data['password'] : null;
        $this->email = (!empty($data['email'])) ? $data['email'] : null;
        $this->firstName = (!empty($data['first_name'])) ? $data['first_name'] : null;
        $this->lastName = (!empty($data['last_name'])) ? $data['last_name'] : null;
        $this->schoolName = (!empty($data['school_name'])) ? $data['school_name'] : null;
        $this->schoolZipCode = (!empty($data['school_zip_code'])) ? $data['school_zip_code'] : null;
        $this->role = (!empty($data['role'])) ? $data['role'] : null;
        $this->confirmationKey = (!empty($data['confirmation_key'])) ? $data['confirmation_key'] : null;
        $this->isConfirmed = (bool) (!empty($data['is_confirmed'])) ? $data['is_confirmed'] : false;

        if(isset($data['password']))
            $this->setPassword($data['password']);

    }

    public function exchangeArray(array $data)
    {

        if (!$this->id) $this->id = (isset($data['id']) && !empty($data['id'])) ? $data['id'] : null;

        $this->username = (!empty($data['username'])) ? $data['username'] : $this->username;
        $this->password = (!empty($data['password'])) ? $data['password'] : $this->password;
        $this->email = (!empty($data['email'])) ? $data['email'] : $this->email;
        $this->firstName = (!empty($data['first_name'])) ? $data['first_name'] : $this->firstName;
        $this->lastName = (!empty($data['last_name'])) ? $data['last_name'] : $this->lastName;
        $this->schoolName = (!empty($data['school_name'])) ? $data['school_name'] : $this->schoolName;
        $this->schoolZipCode = (!empty($data['school_zip_code'])) ? $data['school_zip_code'] : $this->schoolZipCode;
        $this->role = (!empty($data['role'])) ? $data['role'] : $this->role;
        $this->confirmationKey = (!empty($data['confirmation_key'])) ? $data['confirmation_key'] : $this->confirmationKey;
        $this->isConfirmed = (bool) (!empty($data['is_confirmed'])) ? $data['is_confirmed'] : $this->isConfirmed;

        $this->exchangeDates($data);
    }

    public function toData() {

        $data = array(
            'username' => $this->username,
            'email' => $this->email,
            'first_name' => $this->firstName,
            'last_name' => $this->lastName,
            'school_name' => $this->schoolName,
            'school_zip_code' => $this->schoolZipCode,
            'role' => $this->role,
            'confirmation_key' => $this->confirmationKey,
            'is_confirmed' => (int)$this->isConfirmed,
        );

        if ($this->password)
            $data['password'] = $this->password;

        return $data;

    }

    public function getArrayCopy() {
        return $this->toData();
    }

}