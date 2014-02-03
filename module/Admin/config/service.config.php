<?php

use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Admin\Account\Entity as Account;
use Admin\Account\Table as AccountTable;
use Admin\Account\Service as AccountService;

return array(

    'factories' => array(
        // Accounts
        'AccountTableGateway' => function ($sm) {
                $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                $resultSetPrototype = new ResultSet();
                $resultSetPrototype->setArrayObjectPrototype(new Account());
                return new TableGateway('accounts', $dbAdapter, null, $resultSetPrototype);
            },
        'AccountTable' =>  function($sm) {
                $tableGateway = $sm->get('AccountTableGateway');
                $table = new AccountTable($tableGateway);
                return $table;
            },
        'AccountService' => function ($sm) {
                return new AccountService($sm->get('AccountTable'));
            },

    )
);