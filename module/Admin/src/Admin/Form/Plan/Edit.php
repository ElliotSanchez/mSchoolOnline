<?php

namespace Admin\Form\Plan;

use Zend\Form\Form;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\Input;
use Zend\Validator AS ZendValidator;

class Edit extends Form
{

    public function __construct() {

        parent::__construct('plan-edit');

        $this->add(array(
            'name' => 'name',
            'options' => array(

            ),
            'type'  => 'Text',
        ));

        $this->add(array(
            'name' => 'short_code',
            'options' => array(

            ),
            'type'  => 'Text',
        ));

        $this->add(array(
            'name' => 'is_active',
            'options' => array(

            ),
            'type'  => 'Checkbox',
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
        $shortCodeInput = new Input('short_code');
        $isActive = new Input('is_active');

        $inputFilter = new InputFilter();

        $inputFilter->add($nameInput);
        $inputFilter->add($shortCodeInput);
        $inputFilter->add($isActive);

        $this->setInputFilter($inputFilter);

    }

}