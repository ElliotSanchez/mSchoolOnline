<?php

namespace Admin\Authentication;

use Admin\Student\Service as StudentUserService;
use Zend\Authentication\AuthenticationService as ZendAuthService;
use \Zend\Session\Container as SessionContainer;

class StudentService extends AbstractService {

    protected $studentService;

    public function __construct(StudentUserService $teacherService, ZendAuthService $authService, SessionContainer $sessionContainer) {
        parent::__construct($authService, $sessionContainer);
        $this->studentService = $teacherService;
    }

    public function getUserService() {
        return $this->studentService;
    }

}