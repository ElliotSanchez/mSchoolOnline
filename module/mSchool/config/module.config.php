<?php

return array(
    'router' => array(
        'routes' => array(
            'mschool' => array(
                'type' => 'Hostname',
                'options' => array(
                    'route' => 'school.mschool.lp',
//                    'constraints' => array(
//                        'subdomain' => '[a-zA-Z0-9_-]*',
//                    ),
                    'defaults' => array(
//                        '__NAMESPACE__' => 'MSchool\Controller',  // REMOVE OF THIS LINE IS IMPORTANT; OTHER WISE THE MSchool MODULE HAS TROUBLE
                        'module'        => 'MSchool',
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
                                'controller' => 'MSchool\Controller\Index',
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
                                'controller' => 'Mschool\Controller\Auth',
                                'action'     => 'login',
                            ),
                        ),
                    ),
                    'teacher_login' => array(
                        'type' => 'Zend\Mvc\Router\Http\Literal',
                        'options' => array(
                            'route'    => '/teacher/login',
                            'defaults' => array(
                                'controller' => 'Mschool\Controller\Auth',
                                'action'     => 'teacher-login',
                            ),
                        ),
                    ),


                    'logout' => array(
                        'type' => 'Zend\Mvc\Router\Http\Literal',
                        'options' => array(
                            'route'    => '/logout',
                            'defaults' => array(
                                'controller' => 'MSchool\Controller\Auth',
                                'action'     => 'logout',
                            ),
                        ),
                    ),
                    'authenticate' => array(
                        'type' => 'Zend\Mvc\Router\Http\Literal',
                        'options' => array(
                            'route'    => '/authenticate',
                            'defaults' => array(
                                'controller' => 'MSchool\Controller\Auth',
                                'action'     => 'authenticate',
                            ),
                        ),
                    ),

                    // PATHWAY
                    'pathway' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route'    => '/pathway',
                            'defaults' => array(
                                'controller' => 'MSchool\Controller\Pathway',
                                'action'     => 'index',
                            ),
                        ),
                    ),

                    'pathway_next' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route'    => '/pathway/next',
                            'defaults' => array(
                                'controller' => 'MSchool\Controller\Pathway',
                                'action'     => 'next',
                            ),
                        ),
                    ),

                    'pathway_previous' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route'    => '/pathway/previous',
                            'defaults' => array(
                                'controller' => 'MSchool\Controller\Pathway',
                                'action'     => 'previous',
                            ),
                        ),
                    ),

                    'pathway_reset' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route'    => '/pathway/reset',
                            'defaults' => array(
                                'controller' => 'MSchool\Controller\Pathway',
                                'action'     => 'reset',
                            ),
                        ),
                    ),

                    'pathway_timer' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route'    => '/pathway/timer',
                            'defaults' => array(
                                'controller' => 'MSchool\Controller\Pathway',
                                'action'     => 'timer',
                            ),
                        ),
                    ),

                    // PAGES
                    'page_intro' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route'    => '/page/intro',
                            'defaults' => array(
                                'controller' => 'MSchool\Controller\Pages',
                                'action'     => 'intro',
                            ),
                        ),
                    ),
                    'page_activity' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route'    => '/page/activity',
                            'defaults' => array(
                                'controller' => 'MSchool\Controller\Pages',
                                'action'     => 'activity',
                            ),
                        ),
                    ),
                    'page_progress' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route'    => '/page/progress',
                            'defaults' => array(
                                'controller' => 'MSchool\Controller\Pages',
                                'action'     => 'progress',
                            ),
                        ),
                    ),

                ),
            ), // HOSTNAME ROUTE
        ), // ROUTES
    ), // ROUTER

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
            'MSchool\Controller\Index' => 'MSchool\Controller\IndexController',
            'MSchool\Controller\Auth' => 'MSchool\Controller\AuthController',
            'MSchool\Controller\Pages' => 'MSchool\Controller\PagesController',
            'MSchool\Controller\Pathway' => 'MSchool\Controller\PathwayController',
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
            'mschool/index/index' => __DIR__ . '/../view/mschool/index/index.phtml',
            'error/404'               => __DIR__ . '/../view/error/404.phtml',
            'error/index'             => __DIR__ . '/../view/error/index.phtml',
        ),
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
        'strategies' => array(
            'ViewJsonStrategy',
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
