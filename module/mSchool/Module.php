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

        $this->initRoute($e);

        // SESSION
        $this->bootstrapSession($e);

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

    protected function initRoute(MvcEvent $e) {

        // SUPER IMPORTANT STUFF
        $application   = $e->getApplication();
        $sm            = $application->getServiceManager();
        $router = $sm->get('router');
        $request = $sm->get('request');
        $matchedRoute = $router->match($request);
        if ($matchedRoute->getParam('subdomain')) {
            // THIS IS DONE SO THAT CALLED TO CONTROLLER redirect()->toRoute(...) HAVE THE SUBDOMAIN AVAILABLE
            $router->setDefaultParam('subdomain', $matchedRoute->getParam('subdomain'));
        }

    }
}
