<?php

namespace Admin\Form\Account;

use Zend\Form\Form;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\Input;
use Zend\Validator;
use Zend\Db\Adapter\AdapterInterface;

use Admin\School\Service As SchoolService;
use Admin\Account\Entity As Account;
use Admin\GradeLevel\Service as GradeLevelService;

class StudentEdit extends Form
{
    protected $schoolService;
    protected $gradeLevelService;
    protected $dbAdapter;

    protected $account;

    public function __construct(SchoolService $schoolService, GradeLevelService $gradeLevelService, AdapterInterface $dbAdapter) {

        $this->schoolService = $schoolService;
        $this->gradeLevelService = $gradeLevelService;
        $this->dbAdapter = $dbAdapter;

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
            'name' => 'grade_level_id',
            'options' => array(
                'required' => true,
                'allow_empty' => true,
            ),
            'type'  => 'Select',
        ));

        $gradeLevelOptions = ['' => '-- Select --'];

        foreach ($gradeLevelService->getOrdered() as $gradeLevel) {
            $gradeLevelOptions[$gradeLevel->id] = $gradeLevel->name;
        }

        $this->get('grade_level_id')->setValueOptions($gradeLevelOptions);


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

        // DUPLICATE NUMBER VALIDATOR ADDED ON bind

        $emailInput->setRequired(false);
        $emailInput->allowEmpty(true);

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

    public function bind($student, $flags = \Zend\Form\FormInterface::VALUES_NORMALIZED) {

        parent::bind($student, $flags);

        $this->getInputFilter()->get('number')->getValidatorChain()->attach(new Validator\Db\NoRecordExists(
            array(
                'table' => 'students',
                'field' => 'number',
                'adapter' => $this->dbAdapter,
                'exclude' => array(
                    'field' => 'id',
                    'value' => $student->id
                )
            )
        ));

        return $this;

    }

}