<?php

namespace Admin\Controller\Plugin;

use Zend\Mvc\Controller\Plugin\AbstractPlugin,
    Zend\Session\Container as SessionContainer;

use Admin\User\Entity as AdminUser;
use Admin\Student\Entity as StudentUser;
use Admin\Teacher\Entity as TeacherUser;

class Auth extends AbstractPlugin
{
    protected $sesscontainer ;

    public function doAuthorization($e, $sessionContainer)
    {
        $controller = $e->getTarget();
        $controllerClass = get_class($controller);
        $namespace = substr($controllerClass, 0, strpos($controllerClass, '\\'));

        $user = $sessionContainer->user;

        if ($user && $user instanceof AdminUser && $namespace == 'Admin') {
            return;
        }

        if ($user && $user instanceof TeacherUser && $namespace == 'MSchool') {
            return;
        }

        if ($user && $user instanceof StudentUser && $namespace == 'MSchool') {
            return;
        }

        if ($user && $user instanceof StudentUser && $namespace == 'MSchool') {
            return;
        }

        $match = $e->getRouteMatch();
        $name = $match->getMatchedRouteName();

        // SEE Admin\Module:onBootstrap
        $noAuthWhiteList = array(
            'admin/login',
            'admin/authenticate',
            'mschool/login',
            'mschool/authenticate',
            'mschool/teacher_login',
            'public/coach_login');

        if (in_array($name, $noAuthWhiteList)) {
            return;
        }

//        echo 'NAMESPACE: ' . $namespace . '<br>';
//        print_r($user);

        // NOT AUTHENTICATED OR REQUESTED APPROVED RESOURCES
        $router = $e->getRouter();
        $url = $router->assemble(array(), array('name' => 'admin/login'));

        $response = $e->getResponse();
        $response->setStatusCode(302);
        $response->getHeaders()->addHeaderLine('Location', $url);
        $e->stopPropagation();

    }
}