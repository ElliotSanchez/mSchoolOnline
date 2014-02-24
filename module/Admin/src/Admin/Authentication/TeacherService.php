<?php

namespace Admin\Authentication;

use Admin\Teacher\Service as TeacherUserService;
use Zend\Authentication\AuthenticationService as ZendAuthService;
use \Zend\Session\Container as SessionContainer;

class TeacherService extends AbstractService {

    protected $teacherService;

    public function __construct(TeacherUserService $teacherService, ZendAuthService $authService, SessionContainer $sessionContainer) {
        parent::__construct($authService, $sessionContainer);
        $this->teacherService = $teacherService;
    }

    public function getUserService() {
        return $this->teacherService;
    }

}