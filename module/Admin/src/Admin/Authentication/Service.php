<?php

namespace Admin\Authentication;

use Admin\User;
use Zend\Authentication\AuthenticationService as ZendAuthService;
use \Zend\Session\Container as SessionContainer;

class Service extends AbstractService {

    protected $userService;
    protected $authService;
    protected $sessionContainer;

    public function __construct(User\Service $userService, ZendAuthService $authService, SessionContainer $sessionContainer) {
        parent::__construct($authService, $sessionContainer);
        $this->userService = $userService;
    }

    public function getUserService() {
        return $this->userService;
    }

}