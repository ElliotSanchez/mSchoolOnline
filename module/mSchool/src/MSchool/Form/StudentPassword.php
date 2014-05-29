<?php

namespace MSchool\Form;

use Zend\Form\Form;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\Input;
use Zend\Validator;

use Admin\School\Service As SchoolService;
use Admin\Account\Entity As Account;

class StudentPassword extends Form
{
    protected $schoolService;

    protected $account;

    public function __construct() {

        parent::__construct('student-password');

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

        $passwordInput = new Input('password');
        $passwordConfirmInput = new Input('password_confirm');
        $submitInput = new Input('submit');

        $passwordInput->getValidatorChain()->attach(new Validator\Identical('password_confirm'));
        $passwordConfirmInput->getValidatorChain()->attach(new Validator\Identical('password'));

        $passwordInput->setRequired(true);
        $passwordConfirmInput->setRequired(true);

        $inputFilter = new InputFilter();

        $inputFilter->add($passwordInput);
        $inputFilter->add($passwordConfirmInput);
        $inputFilter->add($submitInput);

        $this->setInputFilter($inputFilter);

    }

}