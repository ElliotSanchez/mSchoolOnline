<?php

namespace Admin\Account;

use Admin\ModelAbstract\ServiceAbstract as ServiceAbstract;
use Admin\Account\Entity as Account;

class Service extends ServiceAbstract {

    public function create($data) {

        $account = new Account();

        $account->create($data);

        $account = $this->table->save($account);

        return $account;

    }

    public function getAccountWithSubdomain($subdomain) {
        return $this->table->fetchWith(array('subdomain' => $subdomain))->current();
    }

}