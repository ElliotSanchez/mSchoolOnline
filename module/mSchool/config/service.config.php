<?php

namespace MSchool;

use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Admin\User\Entity as User;
use Admin\User\Table as UserTable;
use Admin\User\Service as UserService;
use Admin\Account\Entity as Account;
use Admin\Account\Table as AccountTable;
use Admin\Account\Service as AccountService;
use Admin\School\Entity as School;
use Admin\School\Table as SchoolTable;
use Admin\School\Service as SchoolService;
use Admin\Teacher\Entity as Teacher;
use Admin\Teacher\Table as TeacherTable;
use Admin\Teacher\Service as TeacherService;
use Admin\Student\Entity as Student;
use Admin\Student\Table as StudentTable;
use Admin\Student\Service as StudentService;

use Zend\Authentication\AuthenticationService as ZendAuthService;
use Admin\Authentication\Service as AdminAuthService;

return array(

    'factories' => array(
        // SESSION
        'StudentSessionContainer' => function ($sm) {
              return new \Zend\Session\Container('student');
            },

        // PATHWAY
        'PathwayService' => function ($sm) {
                return new Pathway\Service($sm->get('ResourceService'));
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
//    'invokables' => array(
//        'LoginForm' => 'MSchool\Form\Auth\Login',
//    ),
);