<?php

namespace Admin\Authentication;
use Zend\Authentication\Adapter\AdapterInterface;
use Zend\Authentication\Result as AuthResult;
use Admin\User\UserAbstract as UserAbstract;

class Adapter extends UserAbstract implements AdapterInterface
{
    private $user;
    private $username;
    private $password;

    public function __construct(UserAbstract $user, $username, $password)
    {
        $this->user = $user;
        $this->username = $username;
        $this->password = $password;
    }

    public function authenticate()
    {
        if ($this->user->validatePassword($this->password)) {
            $result = AuthResult::SUCCESS;
        } else {
            $result = AuthResult::FAILURE;
        }

        return new AuthResult($result, array('user' => $this->user));
    }
}