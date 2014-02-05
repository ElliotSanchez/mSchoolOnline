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
    public function onBootstrap(MvcEvent $e)
    {
        $eventManager        = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);

        // AUTH
        $eventManager->attach(MvcEvent::EVENT_DISPATCH, function($e) {

            $match = $e->getRouteMatch();

            // LOGIN PAGE
            $name = $match->getMatchedRouteName();
            if ($name == 'admin/login' || $name == 'admin/authenticate') {
                return;
            }

            // USER IS AUTHENTICATED
            $authService = new \Zend\Authentication\AuthenticationService();
            if ($authService->hasIdentity()) {
                return;
            }

            // USER IS NOT LOGGED IN SO REDIRECT THEM TO LOGIN
            $router   = $e->getRouter();
            $url      = $router->assemble(array(), array(
                'name' => 'admin/login'
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
}
