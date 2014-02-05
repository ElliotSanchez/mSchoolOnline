<?php

return array(
    'router' => array(
        'routes' => array(
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

            'admin' => array(
                'type' => 'Hostname',
                'options' => array(
                    'route' => 'admin.mschool',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Admin\Controller',
                        'controller'    => 'Index',
                        'action'        => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'default' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/[:controller[/:action]]',
                            'constraints' => array(
                                'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                            ),
                            'defaults' => array(
                                'controller' => 'Index',
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
);
