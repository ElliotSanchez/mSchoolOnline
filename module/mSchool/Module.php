<?php
namespace MSchool;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Admin\Account\Entity as Account;

class Module
{
    protected $subdomain;
    protected static $account;

    public function onBootstrap(MvcEvent $e)
    {
        date_default_timezone_set('America/Chicago');

        $eventManager        = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
        $moduleRouteListener->onRoute($e);

        $this->initRoute($e);

        $this->initAccount($e);

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
        if ($matchedRoute && $matchedRoute->getParam('subdomain')) {
            // THIS IS DONE SO THAT CALLS TO CONTROLLER redirect()->toRoute(...) HAVE THE SUBDOMAIN AVAILABLE
            $router->setDefaultParam('subdomain', $matchedRoute->getParam('subdomain'));
            $this->subdomain = $matchedRoute->getParam('subdomain');
        }

    }

    protected function initAccount(MvcEvent $e) {

        if ($this->subdomain) {

            $application = $e->getApplication();
            $sm = $application->getServiceManager();
            $accountService = $sm->get('AccountService');

            self::$account = $accountService->getAccountWithSubdomain($this->subdomain);

        } else {
            // DEFAULT ACCOUNT
            $sm             = $e->getApplication()->getServiceManager();
            $config         = $sm->get('config');

            //$DEFAULT_ACCOUNT_ID = $config['accounts']['default'];
            $accountService = $sm->get('AccountService');

            self::$account = $accountService->getDefaultAccount();

            $this->subdomain = self::$account->subdomain;
            $router = $sm->get('router');
            $router->setDefaultParam('subdomain', $this->subdomain);

        }

    }

    public static function account() {
        return self::$account;
    }
}
