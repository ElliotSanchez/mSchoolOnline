<?php

namespace Admin\Authentication;

use Admin\User;
use Zend\Authentication\AuthenticationService as ZendAuthService;
use \Zend\Session\Container as SessionContainer;

abstract class AbstractService {

    protected $authService;
    protected $sessionContainer;

    abstract public function getUserService();

    public function __construct(ZendAuthService $authService, SessionContainer $sessionContainer) {
        $this->authService = $authService;
        $this->sessionContainer = $sessionContainer;
    }

    public function authenticate($username, $password) {

        $user = $this->getUserService()->getForUsername($username);

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

    public function getCurrentUser() {
        return $this->sessionContainer->user;
    }

    public function logout() {
        $this->authService->clearIdentity();
        $this->sessionContainer->getManager()->getStorage()->clear('mschool');
    }

}