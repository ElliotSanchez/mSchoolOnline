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
use Admin\Mclass\Entity as Mclass;
use Admin\Mclass\Table as MclassTable;
use Admin\Mclass\Service as MclassService;
use Admin\MclassStudent\Entity as MclassStudent;
use Admin\MclassStudent\Table as MclassStudentTable;
use Admin\MclassStudent\Service as MclassStudentService;
use Admin\MclassTeacher\Entity as MclassTeacher;
use Admin\MclassTeacher\Table as MclassTeacherTable;
use Admin\MclassTeacher\Service as MclassTeacherService;
use Admin\Resource\Entity as Resource;
use Admin\Resource\Table as ResourceTable;
use Admin\Resource\Service as ResourceService;
use Admin\StudentLogin\Entity as StudentLogin;
use Admin\StudentLogin\Table as StudentLoginTable;
use Admin\StudentLogin\Service as StudentLoginService;
use Admin\Sequence\Entity as Sequence;
use Admin\Sequence\Table as SequenceTable;
use Admin\Sequence\Service as SequenceService;
use Admin\Step\Entity as Step;
use Admin\Step\Table as StepTable;
use Admin\Step\Service as StepService;
use Admin\Plan\Entity as Plan;
use Admin\Plan\Table as PlanTable;
use Admin\Plan\Service as PlanService;
use Admin\Pathway\Entity as Pathway;
use Admin\Pathway\Table as PathwayTable;
use Admin\Pathway\Service as PathwayService;
use Admin\PathwayPlan\Entity as PathwayPlan;
use Admin\PathwayPlan\Table as PathwayPlanTable;
use Admin\PathwayPlan\Service as PathwayPlanService;
use Admin\PlanStep\Entity as PlanStep;
use Admin\PlanStep\Table as PlanStepTable;
use Admin\PlanStep\Service as PlanStepService;
use Admin\StudentStep\Entity as StudentStep;
use Admin\StudentStep\Table as StudentStepTable;
use Admin\StudentStep\Service as StudentStepService;
use Admin\Progression\Entity as Progression;
use Admin\Progression\Table as ProgressionTable;
use Admin\Progression\Service as ProgressionService;
use Zend\Authentication\AuthenticationService as ZendAuthService;
use Admin\Authentication\Service as AdminAuthService;
use Admin\Authentication\TeacherService as TeacherAuthService;
use Admin\Authentication\StudentService as StudentAuthService;

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
                return new Admin\Form\Account\StudentAdd($sm->get('SchoolService'), $sm->get('Zend\Db\Adapter\Adapter'));
            },
        'StudentEditForm' => function ($sm) {
                return new Admin\Form\Account\StudentEdit($sm->get('SchoolService'), $sm->get('Zend\Db\Adapter\Adapter'));
            },

        // MCLASSES
        'MclassTableGateway' => function ($sm) {
                $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                $resultSetPrototype = new ResultSet();
                $resultSetPrototype->setArrayObjectPrototype(new Mclass());
                return new TableGateway('mclasses', $dbAdapter, null, $resultSetPrototype);
            },
        'MclassTable' =>  function($sm) {
                $tableGateway = $sm->get('MclassTableGateway');
                $table = new MclassTable($tableGateway);
                return $table;
            },
        'MclassService' => function ($sm) {
                return new MclassService($sm->get('MclassTable'), $sm->get('MclassStudentService'), $sm->get('MclassTeacherService'), $sm->get('StudentService'), $sm->get('TeacherService'));
            },
        'MclassAddForm' => function ($sm) {
                return new Admin\Form\Account\Mclass\Add($sm->get('SchoolService'));
            },
        'MclassEditForm' => function ($sm) {
                return new Admin\Form\Account\Mclass\Edit($sm->get('SchoolService'));
            },
        'MclassStudentsForm' => function ($sm) {
            return new Admin\Form\Account\Mclass\Students($sm->get('MclassService'), $sm->get('StudentService'));
        },
        'MclassTeachersForm' => function ($sm) {
            return new Admin\Form\Account\Mclass\Teachers($sm->get('MclassService'), $sm->get('TeacherService'));
        },

        // MCLASS STUDENTS
        'MclassStudentTableGateway' => function ($sm) {
                $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                $resultSetPrototype = new ResultSet();
                $resultSetPrototype->setArrayObjectPrototype(new MclassStudent());
                return new TableGateway('mclasses_students', $dbAdapter, null, $resultSetPrototype);
            },
        'MclassStudentTable' =>  function($sm) {
                $tableGateway = $sm->get('MclassStudentTableGateway');
                $table = new MclassStudentTable($tableGateway);
                return $table;
            },
        'MclassStudentService' => function ($sm) {
                return new MclassStudentService($sm->get('MclassStudentTable'));
            },

        // MCLASS TEACHERS
        'MclassTeacherTableGateway' => function ($sm) {
            $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
            $resultSetPrototype = new ResultSet();
            $resultSetPrototype->setArrayObjectPrototype(new MclassTeacher());
            return new TableGateway('mclasses_teachers', $dbAdapter, null, $resultSetPrototype);
        },
        'MclassTeacherTable' =>  function($sm) {
            $tableGateway = $sm->get('MclassTeacherTableGateway');
            $table = new MclassTeacherTable($tableGateway);
            return $table;
        },
        'MclassTeacherService' => function ($sm) {
            return new MclassTeacherService($sm->get('MclassTeacherTable'));
        },

        // RESOURCES
        'ResourceTableGateway' => function ($sm) {
                $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                $resultSetPrototype = new ResultSet();
                $resultSetPrototype->setArrayObjectPrototype(new Resource());
                return new TableGateway('resources', $dbAdapter, null, $resultSetPrototype);
            },
        'ResourceTable' =>  function($sm) {
                $tableGateway = $sm->get('ResourceTableGateway');
                $table = new ResourceTable($tableGateway);
                return $table;
            },
        'ResourceService' => function ($sm) {
                return new ResourceService($sm->get('ResourceTable'));
            },
        'ResourceAddForm' => function ($sm) {
                return new Admin\Form\Resources\Add();
            },
        'ResourceEditForm' => function ($sm) {
                return new Admin\Form\Resources\Edit();
            },

        /* >>>>>>>>>> */

        // SEQUENCE
        'SequenceTableGateway' => function ($sm) {
                $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                $resultSetPrototype = new ResultSet();
                $resultSetPrototype->setArrayObjectPrototype(new Sequence());
                return new TableGateway('sequences', $dbAdapter, null, $resultSetPrototype);
            },
        'SequenceTable' =>  function($sm) {
                $tableGateway = $sm->get('SequenceTableGateway');
                $table = new SequenceTable($tableGateway);
                return $table;
            },
        'SequenceService' => function ($sm) {
                return new SequenceService(
                    $sm->get('SequenceTable'),
                    $sm->get('PathwayService'),
                    $sm->get('PlanService'),
                    $sm->get('StepService'),
                    $sm->get('PathwayPlanService'),
                    $sm->get('PlanStepService'),
                    $sm->get('StudentStepService'),
                    $sm->get('ResourceService'),
                    $sm->get('StudentService'),
                    $sm->get('ProgressionService'));
            },

        // STEPS
        'StepTableGateway' => function ($sm) {
                $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                $resultSetPrototype = new ResultSet();
                $resultSetPrototype->setArrayObjectPrototype(new Step());
                return new TableGateway('steps', $dbAdapter, null, $resultSetPrototype);
            },
        'StepTable' =>  function($sm) {
                $tableGateway = $sm->get('StepTableGateway');
                $table = new StepTable($tableGateway);
                return $table;
            },
        'StepService' => function ($sm) {
                return new StepService($sm->get('StepTable'), $sm->get('ResourceService'));
            },
        'StepAddForm' => function ($sm) {
                return new Admin\Form\Step\Add($sm->get('ResourceService'));
            },
        'StepEditForm' => function ($sm) {
                return new Admin\Form\Step\Edit($sm->get('ResourceService'));
            },

        // PLANS
        'PlanTableGateway' => function ($sm) {
                $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                $resultSetPrototype = new ResultSet();
                $resultSetPrototype->setArrayObjectPrototype(new Plan());
                return new TableGateway('plans', $dbAdapter, null, $resultSetPrototype);
            },
        'PlanTable' =>  function($sm) {
                $tableGateway = $sm->get('PlanTableGateway');
                $table = new PlanTable($tableGateway);
                return $table;
            },
        'PlanService' => function ($sm) {
                return new PlanService($sm->get('PlanTable'));
            },
        'PlanAddForm' => function ($sm) {
                return new Admin\Form\Plan\Add();
            },
        'PlanEditForm' => function ($sm) {
                return new Admin\Form\Plan\Edit();
            },
        'PlanStepAddForm' => function ($sm) {
                return new Admin\Form\Plan\StepAdd($sm->get('StepService'));
            },
        'PlanStepRemoveForm' => function ($sm) {
                return new Admin\Form\Plan\StepRemove();
            },

        // PLAN STEPS
        'PlanStepTableGateway' => function ($sm) {
                $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                $resultSetPrototype = new ResultSet();
                $resultSetPrototype->setArrayObjectPrototype(new PlanStep());
                return new TableGateway('plan_steps', $dbAdapter, null, $resultSetPrototype);
            },
        'PlanStepTable' =>  function($sm) {
                $tableGateway = $sm->get('PlanStepTableGateway');
                $table = new PlanStepTable($tableGateway);
                return $table;
            },
        'PlanStepService' => function ($sm) {
                return new PlanStepService($sm->get('PlanStepTable'), $sm->get('PlanService'), $sm->get('StepService'));
            },

        // PATHWAYS
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
                return new PathwayService($sm->get('PathwayTable'));
            },
        'PathwayAddForm' => function ($sm) {
                return new Admin\Form\Pathway\Add();
            },
        'PathwayEditForm' => function ($sm) {
                return new Admin\Form\Pathway\Edit();
            },
        'PathwayPlanAddForm' => function ($sm) {
                return new Admin\Form\Pathway\PlanAdd($sm->get('PlanService'));
            },
        'PathwayPlanRemoveForm' => function ($sm) {
                return new Admin\Form\Pathway\PlanRemove();
            },

        // PATHWAY PLANS
        'PathwayPlanTableGateway' => function ($sm) {
                $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                $resultSetPrototype = new ResultSet();
                $resultSetPrototype->setArrayObjectPrototype(new PathwayPlan());
                return new TableGateway('pathway_plans', $dbAdapter, null, $resultSetPrototype);
            },
        'PathwayPlanTable' =>  function($sm) {
                $tableGateway = $sm->get('PathwayPlanTableGateway');
                $table = new PathwayPlanTable($tableGateway);
                return $table;
            },
        'PathwayPlanService' => function ($sm) {
                return new PathwayPlanService($sm->get('PathwayPlanTable'), $sm->get('PathwayService'), $sm->get('PlanService'));
            },

        // STUDENT STEPS
        'StudentStepTableGateway' => function ($sm) {
                $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                $resultSetPrototype = new ResultSet();
                $resultSetPrototype->setArrayObjectPrototype(new StudentStep());
                return new TableGateway('student_steps', $dbAdapter, null, $resultSetPrototype);
            },
        'StudentStepTable' =>  function($sm) {
                $tableGateway = $sm->get('StudentStepTableGateway');
                $table = new StudentStepTable($tableGateway);
                return $table;
            },
        'StudentStepService' => function ($sm) {
                return new StudentStepService($sm->get('StudentStepTable'));
            },

        // PROGRESSIONS
        'ProgressionTableGateway' => function ($sm) {
                $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                $resultSetPrototype = new ResultSet();
                $resultSetPrototype->setArrayObjectPrototype(new Progression());
                return new TableGateway('progressions', $dbAdapter, null, $resultSetPrototype);
            },
        'ProgressionTable' =>  function($sm) {
                $tableGateway = $sm->get('ProgressionTableGateway');
                $table = new ProgressionTable($tableGateway);
                return $table;
            },
        'ProgressionService' => function ($sm) {
                return new ProgressionService($sm->get('ProgressionTable'));
            },

        /* <<<<<<<<<< */
        
        
        // UPLOAD
        'StudentUploadForm' => function ($sm) {
            return new Admin\Form\Upload\Students();
        },

        'PathwaysUploadForm' => function ($sm) {
            return new Admin\Form\Upload\Pathways();
        },

        // ADMIN AUTH
        'AdminAuthService' => function ($sm) {
            return new AdminAuthService($sm->get('UserService'), new ZendAuthService(), $sm->get('AuthSessionContainer'));
        },
        'TeacherAuthService' => function ($sm) {
            return new TeacherAuthService($sm->get('TeacherService'), new ZendAuthService(), $sm->get('AuthSessionContainer'));
        },
        'StudentAuthService' => function ($sm) {
            return new StudentAuthService($sm->get('StudentService'), new ZendAuthService(), $sm->get('AuthSessionContainer'), $sm->get('StudentLoginService'));
        },

        // AUTH SESSION
        'AuthSessionContainer' => function ($sm) {
            return new \Zend\Session\Container('mschool');
        },

        // STUDENT LOGINS
        'StudentLoginTableGateway' => function ($sm) {
                $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                $resultSetPrototype = new ResultSet();
                $resultSetPrototype->setArrayObjectPrototype(new StudentLogin());
                return new TableGateway('student_logins', $dbAdapter, null, $resultSetPrototype);
            },
        'StudentLoginTable' =>  function($sm) {
                $tableGateway = $sm->get('StudentLoginTableGateway');
                $table = new StudentLoginTable($tableGateway);
                return $table;
            },
        'StudentLoginService' => function ($sm) {
                return new StudentLoginService($sm->get('StudentLoginTable'));
            },

    ),
    'invokables' => array(
        'AdminLoginForm' => 'Admin\Form\Auth\Login',
    ),

    'initializers' => array(
        function ($instance, $sm) {
            if ($instance instanceof \Zend\Db\Adapter\AdapterAwareInterface) {
                $instance->setDbAdapter($sm->get('Zend\Db\Adapter\Adapter'));
            }
        }
    ),

);