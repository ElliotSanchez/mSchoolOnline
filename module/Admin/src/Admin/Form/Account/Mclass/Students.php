<?php

namespace Admin\Form\Account\Mclass;

use Zend\Form\Form;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\Input;
use Zend\Validator;

use Admin\Mclass\Service As MclassService;
use Admin\Student\Service As StudentService;
use Admin\Account\Entity As Account;
use Admin\Mclass\Entity As Mclass;

class Students extends Form
{

    protected $mclassService;
    protected $studentService;

    protected $account;
    protected $mclass;

    public function __construct(MclassService $mclassService, StudentService $studentService) {

        $this->mclassService = $mclassService;
        $this->studentService = $studentService;

        parent::__construct('mclass-students');

        $this->add(array(
            'name' => 'account_id',
            'options' => array(

            ),
            'type'  => 'Hidden',
        ));

        $this->add(array(
            'name' => 'submit',
            'options' => array(

            ),
            'attributes' => array(
                'type'  => 'submit',
            ),
        ));

//        $nameInput = new Input('name');

        $inputFilter = new InputFilter();

//        $inputFilter->add($nameInput);

        $this->setInputFilter($inputFilter);

    }

    public function setAccount(Account $account) {

        $this->account = $account;

        // ACCOUNT
        $this->get('account_id')->setValue($account->id);

    }

    public function setMclass(Mclass $mclass) {

        $this->mclass = $mclass;

        // ACCOUNT
        //$this->get('mclass_id')->setValue($mclass->id);

        $this->createStudentForms();

    }

    protected function createStudentForms() {

        // ASSIGNED
        $studentsAssigned = $this->mclassService->getStudentsAssignedToMclass($this->mclass);

        $assignedCollection = new \Zend\Form\Element\Collection();

        $assignedCollection->setName('assigned_students');

        foreach ($studentsAssigned as $student) {

            $assignedCollection->add(array(
                'name' => $student->id,
                'options' => array(
                    'label' => $student->getFullName(),

                ),
                'type'  => 'Checkbox',
            ))->get($student->id)->setValue(1);

        }

        $this->add($assignedCollection);

        // AVAILABLE
        $studentsAvailable = $this->mclassService->getStudentsAvailableToMclass($this->mclass);

        $availableCollection = new \Zend\Form\Element\Collection();

        $availableCollection->setName('available_students');

        foreach ($studentsAvailable as $student) {

            $availableCollection->add(array(
                'name' => $student->id,
                'options' => array(
                    'label' => $student->getFullName(),
                ),
                'type'  => 'Checkbox',
            ));

        }

        $this->add($availableCollection);

    }

}