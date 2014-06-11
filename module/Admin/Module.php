<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Admin;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;

class Module
{
    public static $studentPasswordKey = null;

    public function onBootstrap(MvcEvent $e)
    {
        date_default_timezone_set('America/Chicago');

        $eventManager        = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);

        $application   = $e->getApplication();
        $sm            = $application->getServiceManager();
        $sharedManager = $application->getEventManager()->getSharedManager();

        $this->loadView($e);

        $router = $sm->get('router');
        $request = $sm->get('request');

        $matchedRoute = $router->match($request);

        $sesionContainer = $sm->get('AuthSessionContainer');
        if (null !== $matchedRoute) {
            $sharedManager->attach('Zend\Mvc\Controller\AbstractActionController','dispatch',
                function($e) use ($sm, $sesionContainer) {
                    $sm->get('ControllerPluginManager')->get('AuthPlugin')
                        ->doAuthorization($e, $sesionContainer);
                },2
            );
        }

        $this->loadKeys($e);

        // CURRENT USER
        $sharedManager->attach(__NAMESPACE__, 'dispatch', function($e) use ($sm) {
            $controller = $e->getTarget();
            $controller->layout()->currentUser = $sm->get('AdminAuthService')->getCurrentUser();
        }, 100);

        // AUTH
        $eventManager->attach(MvcEvent::EVENT_DISPATCH, function($e) {

            $match = $e->getRouteMatch();

            // LOGIN PAGE
            $name = $match->getMatchedRouteName();

            if (in_array($name, array('admin/login', 'admin/authenticate', 'mschool/login', 'mschool/teacher_login', 'mschool/authenticate'))) {
                return;
            }

            // USER IS AUTHENTICATED
            $authService = new \Zend\Authentication\AuthenticationService();
            if ($authService->hasIdentity()) {
                return;
            }

            // DETERMINE MODULE
            $module = (strpos($name, 'mschool') !== false) ? ('mschool') : ('admin');

            // USER IS NOT LOGGED IN SO REDIRECT THEM TO LOGIN
            $router   = $e->getRouter();
            $url      = $router->assemble(array(), array(
                'name' => $module.'/login',
            ));

            $response = $e->getResponse();
            $response->getHeaders()->addHeaderLine('Location', $url);
            $response->setStatusCode(302);

            return $response;

        }, 100);
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

    public function getServiceConfig()
    {
        return include __DIR__ . '/config/service.config.php';
    }

    protected function loadKeys(MvcEvent $e) {
        $sm             = $e->getApplication()->getServiceManager();
        $config         = $sm->get('config');
        self::$studentPasswordKey = $config['encryption']['student_key'];
    }

    protected function loadView(MvcEvent $e) {
        $sm             = $e->getApplication()->getServiceManager();
        $config         = $sm->get('config');

        $viewModel = $e->getApplication()->getMvcEvent()->getViewModel();

        $themeColor = 'green';

        switch ($config['environment']['type']) {
            case 'development': $themeColor = 'green'; break;
            case 'qa': $themeColor = 'purple'; break;
            case 'production': $themeColor = 'green'; break;
        }

        $viewModel->themeColor = $themeColor;
    }
}
