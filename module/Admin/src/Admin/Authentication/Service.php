<?php

namespace Admin\Authentication;

use Admin\User;
use Zend\Authentication\AuthenticationService as ZendAuthService;

class Service {

    protected $userService;
    protected $authService;

    public function __construct(User\Service $userService, ZendAuthService $authService) {
        $this->userService = $userService;
        $this->authService = $authService;
    }

    public function authenticate($username, $password) {

        $user = $this->userService->getForUsername($username);

        if (!$user) return false;

        //if (!$user->isActive) return false;

        $authAdapter = new Adapter($user, $username, $password);

        $result = $this->authService->authenticate($authAdapter);

        if ($result->isValid()) {
            return $user;
        } else {
            return false;
        }

    }

    public function getZendAuthService() {
        return $this->authService;
    }

}