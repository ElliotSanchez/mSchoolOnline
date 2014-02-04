<?php

namespace Admin\Form\Account;

use Zend\Form\Form;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\Input;
use Zend\Validator;

class Add extends Form
{
    public function __construct() {

        parent::__construct('account-add');

        $this->add(array(
            'name' => 'name',
            'options' => array(

            ),
            'type'  => 'Text',
        ));

        $this->add(array(
            'name' => 'subdomain',
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
        $subdomainInput = new Input('subdomain');
        $submitInput = new Input('submit');

        $inputFilter = new InputFilter();

        $inputFilter->add($nameInput);
        $inputFilter->add($subdomainInput);
        $inputFilter->add($submitInput);

        $this->setInputFilter($inputFilter);

    }

}