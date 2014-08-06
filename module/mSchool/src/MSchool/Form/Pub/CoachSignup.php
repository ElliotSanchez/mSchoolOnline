<?php

namespace MSchool\Form\Pub;

use Zend\Form\Form;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\Input;
use Zend\Validator;

class CoachSignup extends Form
{
    public function __construct() {

        parent::__construct('teacher-login');

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
            'name' => 'password',
            'options' => array(

            ),
            'type'  => 'Password',
        ));

        $this->add(array(
            'name' => 'school_name',
            'options' => array(

            ),
            'type'  => 'Text',
        ));

        $this->add(array(
            'name' => 'school_zip_code',
            'options' => array(

            ),
            'type'  => 'Text',
        ));

        $this->add(array(
            'type' => 'Radio',
            'name' => 'role',
            'options' => array(
//                'label' => 'What is your gender ?',
                'value_options' => array(
                    'admin' => 'Administrator',
                    'teacher' => 'Teacher',
                    'parent' => 'Parent',
                ),
            ),
        ));

        $this->add(array(
            'name' => 'submit',
            'options' => array(

            ),
            'attributes' => array(
                'type'  => 'submit',
            ),
        ));

        $firstname = new Input('first_name');
        $lastname = new Input('last_name');
        $email = new Input('email');
        $passwordInput = new Input('password');
        $schoolName = new Input('school_name');
        $schoolZipCode = new Input('school_zip_code');
        $role = new Input('role');
        $submitInput = new Input('submit');

        $emailValidator = new Validator\EmailAddress();
        $emailValidator->setMessage('Please enter a valid email address.', Validator\EmailAddress::INVALID_FORMAT);
        $email->getValidatorChain()->attach($emailValidator);

        $inputFilter = new InputFilter();

        $inputFilter->add($firstname);
        $inputFilter->add($lastname);
        $inputFilter->add($email);
        $inputFilter->add($passwordInput);
        $inputFilter->add($schoolName);
        $inputFilter->add($schoolZipCode);
        $inputFilter->add($role);
        $inputFilter->add($submitInput);

        $this->setInputFilter($inputFilter);

    }

}