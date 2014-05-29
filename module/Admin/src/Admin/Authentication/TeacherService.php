<?php

namespace Admin\Authentication;

use Admin\Teacher\Service as TeacherUserService;
use Zend\Authentication\AuthenticationService as ZendAuthService;
use \Zend\Session\Container as SessionContainer;
use Admin\Account\Entity as Account;

class TeacherService extends AbstractService {

    protected $teacherService;

    public function __construct(TeacherUserService $teacherService, ZendAuthService $authService, SessionContainer $sessionContainer) {
        parent::__construct($authService, $sessionContainer);
        $this->teacherService = $teacherService;
    }

    public function getUserService() {
        return $this->teacherService;
    }

    public function authenticate($username, $password) {

        $user = $this->authenticateWithAccount($username, $password, \MSchool\Module::account());

        return $user;
    }

    private function authenticateWithAccount($username, $password, Account $account) {

        $user = $this->getUserService()->getForUsernameWithAccount($username, $account);

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

}