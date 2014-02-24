<?php

namespace MSchool;

use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;

use MSchool\Pathway\Entity as Pathway;
use MSchool\Pathway\Table as PathwayTable;
use MSchool\Pathway\Service as PathwayService;

use Zend\Authentication\AuthenticationService as ZendAuthService;
use Admin\Authentication\Service as AdminAuthService;

return array(

    'factories' => array(
        // SESSION
        'StudentSessionContainer' => function ($sm) {
              return new \Zend\Session\Container('student');
            },

        // PATHWAY
        'PathwayTableGateway' => function ($sm) {
                $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                $resultSetPrototype = new ResultSet();
                $resultSetPrototype->setArrayObjectPrototype(new Pathway());
                return new TableGateway('pathways', $dbAdapter, null, $resultSetPrototype);
            },
        'PathwayTable' =>  function($sm) {
                $tableGateway = $sm->get('PathwayTableGateway');
                $table = new PathwayTable($tableGateway);
                return $table;
            },
        'PathwayService' => function ($sm) {
                return new PathwayService($sm->get('PathwayTable'), $sm->get('ResourceService'), $sm->get('StudentService'));
            },


        // USERS
//        'UserTableGateway' => function ($sm) {
//                $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
//                $resultSetPrototype = new ResultSet();
//                $resultSetPrototype->setArrayObjectPrototype(new User());
//                return new TableGateway('users', $dbAdapter, null, $resultSetPrototype);
//            },
//        'UserTable' =>  function($sm) {
//                $tableGateway = $sm->get('UserTableGateway');
//                $table = new UserTable($tableGateway);
//                return $table;
//            },
//        'UserService' => function ($sm) {
//                return new UserService($sm->get('UserTable'));
//            },
//        'UserAddForm' => function ($sm) {
//                return new Admin\Form\Users\Add();
//            },
//        'UserEditForm' => function ($sm) {
//                return new Admin\Form\Users\Edit();
//            },

// AUTH
//        'AdminAuthService' => function ($sm) {
//            return new AdminAuthService($sm->get('UserService'), new ZendAuthService());
//        }

    ),
    'invokables' => array(
        'StudentLoginForm' => 'MSchool\Form\Auth\StudentLogin',
    ),
);