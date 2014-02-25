<?php

namespace Admin\Form\Account\Mclass;

use Zend\Form\Form;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\Input;
use Zend\Validator;
use Zend\Db\Adapter\AdapterInterface;

use Admin\School\Service As SchoolService;
use Admin\Account\Entity As Account;

class Add extends Form
{
    protected $schoolService;

    protected $account;

    public function __construct(SchoolService $schoolService) {

        $this->schoolService = $schoolService;

        parent::__construct('teacher-add');

        $this->add(array(
            'name' => 'account_id',
            'options' => array(

            ),
            'type'  => 'Hidden',
        ));

        $this->add(array(
            'name' => 'name',
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
            'name' => 'submit',
            'options' => array(

            ),
            'attributes' => array(
                'type'  => 'submit',
            ),
        ));

        $nameInput = new Input('name');
        $submitInput = new Input('submit');

        $inputFilter = new InputFilter();

        $inputFilter->add($nameInput);
        $inputFilter->add($submitInput);

        $this->setInputFilter($inputFilter);

    }

    public function setAccount(Account $account) {

        $this->account = $account;

        // ACCOUNT
        $this->get('account_id')->setValue($account->id);

        // SCHOOLS
        $schoolOptions = [];

        foreach ($this->schoolService->getForAccount($this->account) as $school) {
            $schoolOptions[$school->id] = $school->name;
        }

        $this->get('school_id')->setValueOptions($schoolOptions);

    }

}