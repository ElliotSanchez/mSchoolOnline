<?php
namespace MSchool;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;

class Module
{
    public function onBootstrap(MvcEvent $e)
    {
        date_default_timezone_set('America/Chicago');

        $eventManager        = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
        $moduleRouteListener->onRoute($e);

//        $eventManager->attach(MvcEvent::EVENT_DISPATCH, function($e) {
//
//            $match = $e->getRouteMatch();
//
//            //die($match->getMatchedRouteName());
//
//        }, 100);

        // SESSION
        $this->bootstrapSession($e);

//        // AUTH
//        $eventManager->attach(MvcEvent::EVENT_DISPATCH, function($e) {
//
//            $match = $e->getRouteMatch();
//
//            // LOGIN PAGE
//            $name = $match->getMatchedRouteName();
//
//            if (in_array($name, array('admin/login', 'admin/authenticate', 'mschool/login', 'mschool/authenticate'))) {
//                return;
//            }
//
//            if (strpos($name, 'mschool') !== FALSE) return; // NOT PART OF THE mschool MODULE
//
//            // USER IS AUTHENTICATED
//            $authService = new \Zend\Authentication\AuthenticationService();
//            if ($authService->hasIdentity()) {
//                return;
//            }
//
//            // USER IS NOT LOGGED IN SO REDIRECT THEM TO LOGIN
//            $router   = $e->getRouter();
//            $url      = $router->assemble(array(), array(
//                'name' => 'mschool/login'
//            ));
//
//            $response = $e->getResponse();
//            $response->getHeaders()->addHeaderLine('Location', $url);
//            $response->setStatusCode(302);
//
//            return $response;
//
//        }, 100);

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

    public function bootstrapSession($e) {

        $container = new \Zend\Session\Container('student');

//        $session = $e->getApplication()
//            ->getServiceManager()
//            ->get('Zend\Session\SessionManager');
//        $session->start();
//
//        $container = new Container('initialized');
//        if (!isset($container->init)) {
//            $session->regenerateId(true);
//            $container->init = 1;
//        }


    }

}
