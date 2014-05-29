<?php

namespace Admin\Form\Account\Mclass;

use Zend\Form\Form;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\Input;
use Zend\Validator;

use Admin\Mclass\Service As MclassService;
use Admin\Teacher\Service As TeacherService;
use Admin\Account\Entity As Account;
use Admin\Mclass\Entity As Mclass;

class Teachers extends Form
{

    protected $mclassService;
    protected $teacherService;

    protected $account;
    protected $mclass;

    public function __construct(MclassService $mclassService, TeacherService $teacherService) {

        $this->mclassService = $mclassService;
        $this->teacherService = $teacherService;

        parent::__construct('mclass-teachers');

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

        $this->createTeacherForms();

    }

    protected function createTeacherForms() {

        // ASSIGNED
        $teachersAssigned = $this->mclassService->getTeachersAssignedToMclass($this->mclass);

        $assignedCollection = new \Zend\Form\Element\Collection();

        $assignedCollection->setName('assigned_teachers');

        foreach ($teachersAssigned as $teacher) {

            $assignedCollection->add(array(
                'name' => $teacher->id,
                'options' => array(
                    'label' => $teacher->getFullName(),

                ),
                'type'  => 'Checkbox',
            ))->get($teacher->id)->setValue(1);

        }

        $this->add($assignedCollection);

        // AVAILABLE
        $teachersAvailable = $this->mclassService->getTeachersAvailableToMclass($this->mclass);

        $availableCollection = new \Zend\Form\Element\Collection();

        $availableCollection->setName('available_teachers');

        foreach ($teachersAvailable as $teacher) {

            $availableCollection->add(array(
                'name' => $teacher->id,
                'options' => array(
                    'label' => $teacher->getFullName(),
                ),
                'type'  => 'Checkbox',
            ));

        }

        $this->add($availableCollection);

    }

}