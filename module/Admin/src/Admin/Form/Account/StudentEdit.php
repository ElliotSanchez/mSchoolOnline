<?php

namespace Admin\Form\Account;

use Zend\Form\Form;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\Input;
use Zend\Validator;

use Admin\School\Service As SchoolService;
use Admin\Account\Entity As Account;

class StudentEdit extends Form
{
    protected $schoolService;

    protected $account;

    public function __construct(SchoolService $schoolService) {

        $this->schoolService = $schoolService;

        parent::__construct('student-edit');

        $this->add(array(
            'name' => 'account_id',
            'options' => array(),
            'type' => 'Hidden',
        ));

        $this->add(array(
            'name' => 'number',
            'options' => array(

            ),
            'type'  => 'Text',
        ));

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
            'name' => 'school_id',
            'options' => array(
            ),
            'type'  => 'Select',
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

        $numberInput = new Input('number');
        $firstNameInput = new Input('first_name');
        $lastNameInput = new Input('last_name');
        $emailInput = new Input('email');
        $passwordInput = new Input('password');
        $passwordConfirmInput = new Input('password_confirm');
        $submitInput = new Input('submit');

        $passwordInput->getValidatorChain()->attach(new Validator\Identical('password_confirm'));
        $passwordConfirmInput->getValidatorChain()->attach(new Validator\Identical('password'));

        $passwordInput->setRequired(false);
        $passwordConfirmInput->setRequired(false);

        $inputFilter = new InputFilter();

        $inputFilter->add($numberInput);
        $inputFilter->add($firstNameInput);
        $inputFilter->add($lastNameInput);
        $inputFilter->add($emailInput);
        $inputFilter->add($passwordInput);
        $inputFilter->add($passwordConfirmInput);
        $inputFilter->add($submitInput);

        $this->setInputFilter($inputFilter);

    }

    public function setAccount(Account $account) {
        $this->account = $account;

        // ACCOUNT
        $this->get('account_id')->setValue($account->id);

        // SCHOOL
        $schoolOptions = [];

        foreach ($this->schoolService->getForAccount($this->account) as $school) {
            $schoolOptions[$school->id] = $school->name;
        }

        $this->get('school_id')->setValueOptions($schoolOptions);

    }

}