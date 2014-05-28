<?php

namespace Admin\Authentication;

use Admin\Student\Service as StudentUserService;
use Zend\Authentication\AuthenticationService as ZendAuthService;
use \Zend\Session\Container as SessionContainer;
use Admin\StudentLogin\Service as StudentLoginService;
use Admin\Account\Entity as Account;

class StudentService extends AbstractService {

    protected $studentService;
    protected $loginService;

    public function __construct(StudentUserService $teacherService, ZendAuthService $authService, SessionContainer $sessionContainer, StudentLoginService $loginService) {
        parent::__construct($authService, $sessionContainer);
        $this->studentService = $teacherService;
        $this->loginService = $loginService;
    }

    public function getUserService() {
        return $this->studentService;
    }

    public function authenticate($username, $password) {

        $user = $this->authenticateWithAccount($username, $password, \MSchool\Module::account());

        if ($user) {
            $this->loginService->recordLogin($user, new \DateTime());
        }

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