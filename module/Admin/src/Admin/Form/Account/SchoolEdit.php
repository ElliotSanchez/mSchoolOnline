<?php

namespace Admin\Form\Account;

use Zend\Form\Form;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\Input;
use Zend\Validator;

class SchoolEdit extends Form
{
    public function __construct() {

        parent::__construct('account-edit');

        $this->add(array(
            'name' => 'name',
            'options' => array(

            ),
            'type'  => 'Text',
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

}