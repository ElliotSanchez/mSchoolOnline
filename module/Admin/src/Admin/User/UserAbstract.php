<?php

namespace Admin\User;

use Admin\ModelAbstract\EntityAbstract;

abstract class UserAbstract extends EntityAbstract {

    public function exchangeArray(array $data)
    {
        parent::exchangeArray($data);
    }

    public function toData() {
        return array();
    }

    public function setPassword($password) {

        $bcrypt = new \Zend\Crypt\Password\Bcrypt();

        $this->password = $bcrypt->create($password);

    }

    public function validatePassword($password) {

        $bcrypt = new \Zend\Crypt\Password\Bcrypt();

        return $bcrypt->verify($password, $this->password);

    }

}