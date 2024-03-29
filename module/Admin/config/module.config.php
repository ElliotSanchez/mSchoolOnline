<?php

return array(
    'router' => array(
        'routes' => array(
            'admin' => array(
                'type' => 'Hostname',
                'options' => array(
                    'route' => 'admin.mschool.today',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Admin\Controller',
                        'module'        => 'Admin',
                        'controller'    => 'Index',
                        'action'        => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
//                    'default' => array(
//                        'type'    => 'Segment',
//                        'options' => array(
//                            'route'    => '/[:controller[/:action]]',
//                            'constraints' => array(
//                                'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
//                                'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
//                            ),
//                            'defaults' => array(
//                                'controller' => 'Index',
//                                'action'     => 'index',
//                            ),
//                        ),
//                    ),
                    'home' => array(
                        'type' => 'Zend\Mvc\Router\Http\Literal',
                        'options' => array(
                            'route'    => '/',
                            'defaults' => array(
                                'controller' => 'Admin\Controller\Index',
                                'action'     => 'index',
                            ),
                        ),
                    ),

                    // AUTH
                    'login' => array(
                        'type' => 'Zend\Mvc\Router\Http\Literal',
                        'options' => array(
                            'route'    => '/login',
                            'defaults' => array(
                                'controller' => 'Admin\Controller\Auth',
                                'action'     => 'login',
                            ),
                        ),
                    ),
                    'logout' => array(
                        'type' => 'Zend\Mvc\Router\Http\Literal',
                        'options' => array(
                            'route'    => '/logout',
                            'defaults' => array(
                                'controller' => 'Admin\Controller\Auth',
                                'action'     => 'logout',
                            ),
                        ),
                    ),
                    'authenticate' => array(
                        'type' => 'Zend\Mvc\Router\Http\Literal',
                        'options' => array(
                            'route'    => '/authenticate',
                            'defaults' => array(
                                'controller' => 'Admin\Controller\Auth',
                                'action'     => 'authenticate',
                            ),
                        ),
                    ),

                    'dashboard' => array(
                        'type' => 'Zend\Mvc\Router\Http\Literal',
                        'options' => array(
                            'route'    => '/dashboard',
                            'defaults' => array(
                                'controller' => 'Admin\Controller\Index',
                                'action'     => 'index',
                            ),
                        ),
                    ),

                    // USERS
                    'users' => array(
                        'type' => 'Zend\Mvc\Router\Http\Literal',
                        'options' => array(
                            'route'    => '/users',
                            'defaults' => array(
                                'controller' => 'Admin\Controller\Users',
                                'action'     => 'index',
                            ),
                        ),
                    ),

                    'user_add' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route'    => '/user/add',
                            'constraints' => array(
                                'id' => '[0-9]*',
                            ),
                            'defaults' => array(
                                'controller' => 'Admin\Controller\Users',
                                'action'     => 'add',
                            ),
                        ),
                    ),

                    'user_edit' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route'    => '/user/edit/[:id]',
                            'constraints' => array(
                                'id' => '[0-9]*',
                            ),
                            'defaults' => array(
                                'controller' => 'Admin\Controller\Users',
                                'action'     => 'edit',
                            ),
                        ),
                    ),

                    // ACCOUNTS
                    'accounts' => array(
                        'type' => 'Zend\Mvc\Router\Http\Literal',
                        'options' => array(
                            'route'    => '/accounts',
                            'defaults' => array(
                                'controller' => 'Admin\Controller\Accounts',
                                'action'     => 'index',
                            ),
                        ),
                    ),

                    'account_add' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route'    => '/account/add',
                            'defaults' => array(
                                'controller' => 'Admin\Controller\Accounts',
                                'action'     => 'add',
                            ),
                        ),
                    ),

                    'account_edit' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route'    => '/account/edit/[:id]',
                            'constraints' => array(
                                'id' => '[0-9]*',
                            ),
                            'defaults' => array(
                                'controller' => 'Admin\Controller\Accounts',
                                'action'     => 'edit',
                            ),
                        ),
                    ),

                    'account_view' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route'    => '/account/[:id]',
                            'constraints' => array(
                                'id' => '[0-9]*',
                            ),
                            'defaults' => array(
                                'controller' => 'Admin\Controller\Accounts',
                                'action'     => 'view',
                            ),
                        ),
                    ),

                    'account_schools' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route'    => '/account/[:id]/schools',
                            'constraints' => array(
                                'id' => '[0-9]*',
                            ),
                            'defaults' => array(
                                'controller' => 'Admin\Controller\Accounts',
                                'action'     => 'schools',
                            ),
                        ),
                    ),

                    'account_teachers' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route'    => '/account/[:id]/teachers',
                            'constraints' => array(
                                'id' => '[0-9]*',
                            ),
                            'defaults' => array(
                                'controller' => 'Admin\Controller\Accounts',
                                'action'     => 'teachers',
                            ),
                        ),
                    ),

                    'account_students' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route'    => '/account/[:id]/students',
                            'constraints' => array(
                                'id' => '[0-9]*',
                            ),
                            'defaults' => array(
                                'controller' => 'Admin\Controller\Accounts',
                                'action'     => 'students',
                            ),
                        ),
                    ),

                    'account_students_export' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route'    => '/account/[:id]/students/export',
                            'constraints' => array(
                                'id' => '[0-9]*',
                            ),
                            'defaults' => array(
                                'controller' => 'Admin\Controller\Accounts',
                                'action'     => 'students-export',
                            ),
                        ),
                    ),

                    'account_mclasses' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route'    => '/account/[:id]/classes',
                            'constraints' => array(
                                'id' => '[0-9]*',
                            ),
                            'defaults' => array(
                                'controller' => 'Admin\Controller\Accounts',
                                'action'     => 'mclasses',
                            ),
                        ),
                    ),

                    // SCHOOLS
                    'school_add' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route'    => '/account/[:id]/school/add',
                            'constraints' => array(
                                'id' => '[0-9]*',
                            ),
                            'defaults' => array(
                                'controller' => 'Admin\Controller\Accounts',
                                'action'     => 'school-add',
                            ),
                        ),
                    ),

                    'school_edit' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route'    => '/account/[:a_id]/school/[:s_id]/edit',
                            'constraints' => array(
                                'a_id' => '[0-9]*',
                                's_id' => '[0-9]*',
                            ),
                            'defaults' => array(
                                'controller' => 'Admin\Controller\Accounts',
                                'action'     => 'school-edit',
                            ),
                        ),
                    ),

                    // TEACHERS
                    'teacher_add' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route'    => '/account/[:id]/teacher/add',
                            'constraints' => array(
                                'id' => '[0-9]*',
                            ),
                            'defaults' => array(
                                'controller' => 'Admin\Controller\Accounts',
                                'action'     => 'teacher-add',
                            ),
                        ),
                    ),

                    'teacher_edit' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route'    => '/account/[:a_id]/teacher/[:t_id]/edit',
                            'constraints' => array(
                                'a_id' => '[0-9]*',
                                't_id' => '[0-9]*',
                            ),
                            'defaults' => array(
                                'controller' => 'Admin\Controller\Accounts',
                                'action'     => 'teacher-edit',
                            ),
                        ),
                    ),

                    // STUDENTS
                    'student_add' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route'    => '/account/[:id]/student/add',
                            'constraints' => array(
                                'id' => '[0-9]*',
                            ),
                            'defaults' => array(
                                'controller' => 'Admin\Controller\Accounts',
                                'action'     => 'student-add',
                            ),
                        ),
                    ),

                    'student_edit' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route'    => '/account/[:a_id]/student/[:s_id]/edit',
                            'constraints' => array(
                                'a_id' => '[0-9]*',
                                's_id' => '[0-9]*',
                            ),
                            'defaults' => array(
                                'controller' => 'Admin\Controller\Accounts',
                                'action'     => 'student-edit',
                            ),
                        ),
                    ),

                    // MCLASSES
                    'mclass_add' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route'    => '/account/[:id]/mclass/add',
                            'constraints' => array(
                                'id' => '[0-9]*',
                            ),
                            'defaults' => array(
                                'controller' => 'Admin\Controller\Accounts',
                                'action'     => 'mclass-add',
                            ),
                        ),
                    ),

                    'mclass_edit' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route'    => '/account/[:a_id]/mclass/[:m_id]/edit',
                            'constraints' => array(
                                'a_id' => '[0-9]*',
                                'm_id' => '[0-9]*',
                            ),
                            'defaults' => array(
                                'controller' => 'Admin\Controller\Accounts',
                                'action'     => 'mclass-edit',
                            ),
                        ),
                    ),

                    'mclass_students' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route'    => '/account/[:a_id]/mclass/[:m_id]/students',
                            'constraints' => array(
                                'a_id' => '[0-9]*',
                                'm_id' => '[0-9]*',
                            ),
                            'defaults' => array(
                                'controller' => 'Admin\Controller\Accounts',
                                'action'     => 'mclass-students',
                            ),
                        ),
                    ),

                    'mclass_teachers' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route'    => '/account/[:a_id]/mclass/[:m_id]/teachers',
                            'constraints' => array(
                                'a_id' => '[0-9]*',
                                'm_id' => '[0-9]*',
                            ),
                            'defaults' => array(
                                'controller' => 'Admin\Controller\Accounts',
                                'action'     => 'mclass-teachers',
                            ),
                        ),
                    ),

                    // RESOURCES
                    'resources' => array(
                        'type' => 'Zend\Mvc\Router\Http\Literal',
                        'options' => array(
                            'route'    => '/resources',
                            'defaults' => array(
                                'controller' => 'Admin\Controller\Resources',
                                'action'     => 'index',
                            ),
                        ),
                    ),

                    'resource_add' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route'    => '/resource/add',
                            'constraints' => array(
                                'id' => '[0-9]*',
                            ),
                            'defaults' => array(
                                'controller' => 'Admin\Controller\Resources',
                                'action'     => 'add',
                            ),
                        ),
                    ),

                    'resource_edit' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route'    => '/resource/edit/[:id]',
                            'constraints' => array(
                                'id' => '[0-9]*',
                            ),
                            'defaults' => array(
                                'controller' => 'Admin\Controller\Resources',
                                'action'     => 'edit',
                            ),
                        ),
                    ),

                    // PATHWAYS
                    'pathways' => array(
                        'type' => 'Zend\Mvc\Router\Http\Literal',
                        'options' => array(
                            'route'    => '/pathways',
                            'defaults' => array(
                                'controller' => 'Admin\Controller\Pathways',
                                'action'     => 'index',
                            ),
                        ),
                    ),

                    'pathway_add' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route'    => '/pathway/add',
                            'constraints' => array(
                                'id' => '[0-9]*',
                            ),
                            'defaults' => array(
                                'controller' => 'Admin\Controller\Pathways',
                                'action'     => 'add',
                            ),
                        ),
                    ),

                    'pathway_edit' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route'    => '/pathway/edit/[:id]',
                            'constraints' => array(
                                'id' => '[0-9]*',
                            ),
                            'defaults' => array(
                                'controller' => 'Admin\Controller\Pathways',
                                'action'     => 'edit',
                            ),
                        ),
                    ),

                    'pathway_add_plan' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route'    => '/pathway/[:id]/add-plan',
                            'constraints' => array(
                                'id' => '[0-9]*',
                            ),
                            'defaults' => array(
                                'controller' => 'Admin\Controller\Pathways',
                                'action'     => 'add-plan',
                            ),
                        ),
                    ),

                    'pathway_remove_plan' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route'    => '/pathway/[:id]/remove-plan',
                            'constraints' => array(
                                'id' => '[0-9]*',
                            ),
                            'defaults' => array(
                                'controller' => 'Admin\Controller\Pathways',
                                'action'     => 'remove-plan',
                            ),
                        ),
                    ),

                    // PLANS
                    'plans' => array(
                        'type' => 'Zend\Mvc\Router\Http\Literal',
                        'options' => array(
                            'route'    => '/plans',
                            'defaults' => array(
                                'controller' => 'Admin\Controller\Plans',
                                'action'     => 'index',
                            ),
                        ),
                    ),

                    'plan_add' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route'    => '/plan/add',
                            'constraints' => array(
                                'id' => '[0-9]*',
                            ),
                            'defaults' => array(
                                'controller' => 'Admin\Controller\Plans',
                                'action'     => 'add',
                            ),
                        ),
                    ),

                    'plan_edit' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route'    => '/plan/edit/[:id]',
                            'constraints' => array(
                                'id' => '[0-9]*',
                            ),
                            'defaults' => array(
                                'controller' => 'Admin\Controller\Plans',
                                'action'     => 'edit',
                            ),
                        ),
                    ),

                    'plan_add_step' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route'    => '/plan/[:id]/add-step',
                            'constraints' => array(
                                'id' => '[0-9]*',
                            ),
                            'defaults' => array(
                                'controller' => 'Admin\Controller\Plans',
                                'action'     => 'add-step',
                            ),
                        ),
                    ),

                    'plan_remove_step' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route'    => '/plan/[:id]/remove-step',
                            'constraints' => array(
                                'id' => '[0-9]*',
                            ),
                            'defaults' => array(
                                'controller' => 'Admin\Controller\Plans',
                                'action'     => 'remove-step',
                            ),
                        ),
                    ),

                    // STEPS
                    'steps' => array(
                        'type' => 'Zend\Mvc\Router\Http\Literal',
                        'options' => array(
                            'route'    => '/steps',
                            'defaults' => array(
                                'controller' => 'Admin\Controller\Steps',
                                'action'     => 'index',
                            ),
                        ),
                    ),

                    'step_add' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route'    => '/step/add',
                            'constraints' => array(
                                'id' => '[0-9]*',
                            ),
                            'defaults' => array(
                                'controller' => 'Admin\Controller\Steps',
                                'action'     => 'add',
                            ),
                        ),
                    ),

                    'step_edit' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route'    => '/step/edit/[:id]',
                            'constraints' => array(
                                'id' => '[0-9]*',
                            ),
                            'defaults' => array(
                                'controller' => 'Admin\Controller\Steps',
                                'action'     => 'edit',
                            ),
                        ),
                    ),
                    
                    // UPLOAD
                    'upload_students' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route'    => '/account/[:a_id]/school/[:s_id]',
                            'constraints' => array(
                                'a_id' => '[0-9]*',
                                's_id' => '[0-9]*',
                            ),
                            'defaults' => array(
                                'controller' => 'Admin\Controller\Upload',
                                'action'     => 'students',
                            ),
                        ),
                    ),

                    'upload_students_file_template' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route'    => '/upload/students-file-template',
                            'defaults' => array(
                                'controller' => 'Admin\Controller\Upload',
                                'action'     => 'students-file',
                            ),
                        ),
                    ),

                    'upload_pathways' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route'    => '/upload/pathway',
                            'defaults' => array(
                                'controller' => 'Admin\Controller\Upload',
                                'action'     => 'pathways',
                            ),
                        ),
                    ),

                    'upload_pathways_file_template' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route'    => '/upload/pathways-file-template',
                            'defaults' => array(
                                'controller' => 'Admin\Controller\Upload',
                                'action'     => 'pathways-file',
                            ),
                        ),
                    ),

                    'pathways_preview' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route'    => '/pathways/preview',
                            'defaults' => array(
                                'controller' => 'Admin\Controller\Pathways',
                                'action'     => 'preview',
                            ),
                        ),
                    ),

                    // STUDENT SEQUENCES
                    'student_sequences' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route'    => '/student/[:id]/sequences',
                            'constraints' => array(
                                'id' => '[0-9]*',
                            ),
                            'defaults' => array(
                                'controller' => 'Admin\Controller\Students',
                                'action'     => 'sequences',
                            ),
                        ),
                    ),

                    // DROPBOX
                    'import_status' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route'    => '/import/status',
                            'constraints' => array(
                            ),
                            'defaults' => array(
                                'controller' => 'Admin\Controller\Import',
                                'action'     => 'status',
                            ),
                        ),
                    ),

                    'import_action' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route'    => '/import/[:type]',
                            'constraints' => array(
                                'type' => 'iready|digitwhiz-mastery|digitwhiz-time|dreambox-usage|dreambox-standards|stmath-progress|stmath-student|stmath-usage|ttm-student|ttm-overview',
                            ),
                            'defaults' => array(
                                'controller' => 'Admin\Controller\Import',
                                'action'     => 'import',
                            ),
                        ),
                    ),

                    'import_history' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route'    => '/import/history/[:type]',
                            'constraints' => array(
                                'type' => 'iready|digitwhiz-mastery|digitwhiz-time|dreambox-usage|dreambox-standards|stmath-progress|stmath-student|stmath-usage|ttm-student|ttm-overview',
                            ),
                            'defaults' => array(
                                'controller' => 'Admin\Controller\Import',
                                'action'     => 'history',
                            ),
                        ),
                    )

                ),
            ),


            // The following is a route to simplify getting started creating
            // new controllers and actions without needing to create a new
            // module. Simply drop new controllers in, and you can access them
            // using the path /application/:controller/:action
//            'admin' => array(
//                'type'    => 'Literal',
//                'options' => array(
//                    'route'    => '/admin',
//                    'defaults' => array(
//                        '__NAMESPACE__' => 'Admin\Controller',
//                        'controller'    => 'Index',
//                        'action'        => 'index',
//                    ),
//                ),
//                'may_terminate' => true,
//                'child_routes' => array(
//                    'default' => array(
//                        'type'    => 'Segment',
//                        'options' => array(
//                            'route'    => '/[:controller[/:action]]',
//                            'constraints' => array(
//                                'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
//                                'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
//                            ),
//                            'defaults' => array(
//                            ),
//                        ),
//                    ),
//                ),
//            ),
        ),
    ),
    'service_manager' => array(
        'abstract_factories' => array(
            'Zend\Cache\Service\StorageCacheAbstractServiceFactory',
            'Zend\Log\LoggerAbstractServiceFactory',
        ),
        'aliases' => array(
            'translator' => 'MvcTranslator',
        ),
    ),
    'translator' => array(
        'locale' => 'en_US',
        'translation_file_patterns' => array(
            array(
                'type'     => 'gettext',
                'base_dir' => __DIR__ . '/../language',
                'pattern'  => '%s.mo',
            ),
        ),
    ),
    'controllers' => array(
        'invokables' => array(
            'Admin\Controller\Index' => 'Admin\Controller\IndexController',
            'Admin\Controller\Auth' => 'Admin\Controller\AuthController',
            'Admin\Controller\Accounts' => 'Admin\Controller\AccountsController',
            'Admin\Controller\Users' => 'Admin\Controller\UsersController',
            'Admin\Controller\Upload' => 'Admin\Controller\UploadController',
            //'Admin\Controller\Pathways' => 'Admin\Controller\PathwaysController',
            'Admin\Controller\Resources' => 'Admin\Controller\ResourcesController',
            'Admin\Controller\Pathways' => 'Admin\Controller\PathwaysController',
            'Admin\Controller\Plans' => 'Admin\Controller\PlansController',
            'Admin\Controller\Steps' => 'Admin\Controller\StepsController',
            'Admin\Controller\Students' => 'Admin\Controller\StudentsController',
            'Admin\Controller\Import' => 'Admin\Controller\ImportController',
        ),
    ),
    'view_manager' => array(
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'template_map' => array(
            'layout/layout'           => __DIR__ . '/../view/layout/layout.phtml',
            'layout/error'           => __DIR__ . '/../view/layout/error.phtml',
            'admin/index/index' => __DIR__ . '/../view/admin/index/index.phtml',
            'error/404'               => __DIR__ . '/../view/error/404.phtml',
            'error/index'             => __DIR__ . '/../view/error/index.phtml',
        ),
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
    // Placeholder for console routes
    'console' => array(
        'router' => array(
            'routes' => array(
            ),
        ),
    ),
    'controller_plugins' => array(
        'invokables' => array(
            'AuthPlugin' => 'Admin\Controller\Plugin\Auth',
        )
    ),
);
