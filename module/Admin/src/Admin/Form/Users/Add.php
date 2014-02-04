<?php

namespace Admin\Form\Users;

use Zend\Form\Form;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\Input;
use Zend\Validator;

class Add extends Form
{
    protected $schoolService;

    protected $account;

    public function __construct() {

        parent::__construct('user-add');

        $this->add(array(
            'name' => 'first_name',
            'options' => array(

            ),
            'type'  => 'Text',
        ));

        $this->add(array(
            'name' => 'last_name',
            'options' => array(

            ),
            'type'  => 'Text',
        ));

        $this->add(array(
            'name' => 'email',
            'options' => array(

            ),
            'type'  => 'Text',
        ));

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
            'name' => 'password_confirm',
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

        $firstNameInput = new Input('first_name');
        $lastNameInput = new Input('last_name');
        $emailInput = new Input('email');
        $passwordInput = new Input('password');
        $passwordConfirmInput = new Input('password_confirm');
        $submitInput = new Input('submit');

        $passwordInput->getValidatorChain()->attach(new Validator\Identical('password_confirm'));
        $passwordConfirmInput->getValidatorChain()->attach(new Validator\Identical('password'));

        $inputFilter = new InputFilter();

        $inputFilter->add($firstNameInput);
        $inputFilter->add($lastNameInput);
        $inputFilter->add($emailInput);
        $inputFilter->add($passwordInput);
        $inputFilter->add($passwordConfirmInput);
        $inputFilter->add($submitInput);

        $this->setInputFilter($inputFilter);

    }

}