<?php

namespace Admin\Authentication;

use Admin\Student\Service as StudentUserService;
use Zend\Authentication\AuthenticationService as ZendAuthService;
use \Zend\Session\Container as SessionContainer;
use Admin\StudentLogin\Service as StudentLoginService;

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

        $user = parent::authenticate($username, $password);

        if ($user) {
            $this->loginService->recordLogin($user, new \DateTime());
        }

        return $user;
    }

}