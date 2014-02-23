<?php

namespace Admin\Authentication;

use Admin\User;
use Zend\Authentication\AuthenticationService as ZendAuthService;
use \Zend\Session\Container as SessionContainer;

class Service {

    protected $userService;
    protected $authService;
    protected $sessionContainer;

    public function __construct(User\Service $userService, ZendAuthService $authService, SessionContainer $sessionContainer) {
        $this->userService = $userService;
        $this->authService = $authService;
        $this->sessionContainer = $sessionContainer;
    }

    public function authenticate($username, $password) {

        $user = $this->userService->getForUsername($username);

        if (!$user) return false;

        //if (!$user->isActive) return false;

        $authAdapter = new Adapter($user, $username, $password);

        $result = $this->authService->authenticate($authAdapter);

        if ($result->isValid()) {
            $this->sessionContainer->user = $user;
            return $user;
        } else {
            return false;
        }

    }

    public function getZendAuthService() {
        return $this->authService;
    }

    public function logout() {
        $this->authService->clearIdentity();
        $this->sessionContainer->getManager()->getStorage()->clear('mschool');

    }

}