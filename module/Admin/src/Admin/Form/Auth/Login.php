<?php

namespace Admin\Form\Auth;

use Zend\Form\Form;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\Input;
use Zend\Validator;

class Login extends Form
{
    protected $schoolService;

    protected $account;

    public function __construct() {

        parent::__construct('admin-login');

        $this->add(array(
            'name' => 'username',
            'options' => array(

            ),
            'type'  => 'Text',
        ));

        $this->add(array(
            'name' => 'password',
            'options' => array(

            ),
            'type'  => 'Password',
        ));

        $this->add(array(
            'name' => 'submit',
            'options' => array(

            ),
            'attributes' => array(
                'type'  => 'submit',
            ),
        ));

        $username = new Input('username');
        $passwordInput = new Input('password');
        $submitInput = new Input('submit');

        $inputFilter = new InputFilter();

        $inputFilter->add($username);
        $inputFilter->add($passwordInput);
        $inputFilter->add($submitInput);

        $this->setInputFilter($inputFilter);

    }

}