<?php

namespace Admin\User;

use Admin\ModelAbstract\ServiceAbstract as ServiceAbstract;
use Admin\User\Entity as User;

class Service extends ServiceAbstract {

    public function create($data) {

        $user = new User();

        $user->create($data);

        $user = $this->table->save($user);

        return $user;

    }

    public function getForUsername($username) {

        return $this->table->fetchWith(['username' => $username])->current();

    }

}