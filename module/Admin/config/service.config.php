<?php

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
        // USERS
        'UserTableGateway' => function ($sm) {
                $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                $resultSetPrototype = new ResultSet();
                $resultSetPrototype->setArrayObjectPrototype(new User());
                return new TableGateway('users', $dbAdapter, null, $resultSetPrototype);
            },
        'UserTable' =>  function($sm) {
                $tableGateway = $sm->get('UserTableGateway');
                $table = new UserTable($tableGateway);
                return $table;
            },
        'UserService' => function ($sm) {
                return new UserService($sm->get('UserTable'));
            },
        'UserAddForm' => function ($sm) {
                return new Admin\Form\Users\Add();
            },
        'UserEditForm' => function ($sm) {
                return new Admin\Form\Users\Edit();
            },

        // ACCOUNTS
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
        'AccountAddForm' => function ($sm) {
                return new Admin\Form\Account\Add();
            },
        'AccountEditForm' => function ($sm) {
                return new Admin\Form\Account\Edit();
            },

        // SCHOOLS
        'SchoolTableGateway' => function ($sm) {
                $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                $resultSetPrototype = new ResultSet();
                $resultSetPrototype->setArrayObjectPrototype(new School());
                return new TableGateway('schools', $dbAdapter, null, $resultSetPrototype);
            },
        'SchoolTable' =>  function($sm) {
                $tableGateway = $sm->get('SchoolTableGateway');
                $table = new SchoolTable($tableGateway);
                return $table;
            },
        'SchoolService' => function ($sm) {
                return new SchoolService($sm->get('SchoolTable'));
            },
        'SchoolAddForm' => function ($sm) {
                return new Admin\Form\Account\SchoolAdd();
            },
        'SchoolEditForm' => function ($sm) {
                return new Admin\Form\Account\SchoolEdit();
            },

        // TEACHERS
        'TeacherTableGateway' => function ($sm) {
                $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                $resultSetPrototype = new ResultSet();
                $resultSetPrototype->setArrayObjectPrototype(new Teacher());
                return new TableGateway('teachers', $dbAdapter, null, $resultSetPrototype);
            },
        'TeacherTable' =>  function($sm) {
                $tableGateway = $sm->get('TeacherTableGateway');
                $table = new TeacherTable($tableGateway);
                return $table;
            },
        'TeacherService' => function ($sm) {
                return new TeacherService($sm->get('TeacherTable'));
            },
        'TeacherAddForm' => function ($sm) {
                return new Admin\Form\Account\TeacherAdd($sm->get('SchoolService'));
            },
        'TeacherEditForm' => function ($sm) {
                return new Admin\Form\Account\TeacherEdit($sm->get('SchoolService'));
            },

        // STUDENTS
        'StudentTableGateway' => function ($sm) {
                $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                $resultSetPrototype = new ResultSet();
                $resultSetPrototype->setArrayObjectPrototype(new Student());
                return new TableGateway('students', $dbAdapter, null, $resultSetPrototype);
            },
        'StudentTable' =>  function($sm) {
                $tableGateway = $sm->get('StudentTableGateway');
                $table = new StudentTable($tableGateway);
                return $table;
            },
        'StudentService' => function ($sm) {
                return new StudentService($sm->get('StudentTable'));
            },
        'StudentAddForm' => function ($sm) {
                return new Admin\Form\Account\StudentAdd($sm->get('SchoolService'));
            },
        'StudentEditForm' => function ($sm) {
                return new Admin\Form\Account\StudentEdit($sm->get('SchoolService'));
            },

        // UPLOAD
        'StudentUploadForm' => function ($sm) {
            return new Admin\Form\Upload\Students();
        },

        'PathwaysUploadForm' => function ($sm) {
            return new Admin\Form\Upload\Pathways();
        },

        // ADMIN AUTH
        'AdminAuthService' => function ($sm) {
            return new AdminAuthService($sm->get('UserService'), new ZendAuthService());
        }

    ),
    'invokables' => array(
        'AdminLoginForm' => 'Admin\Form\Auth\Login',
    ),
);