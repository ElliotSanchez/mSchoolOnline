<?php

namespace Admin\Form\Pathway;

use Zend\Form\Form;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\Input;
use Zend\Validator AS ZendValidator;

class Add extends Form
{
    public function __construct() {

        parent::__construct('pathway-add');

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
            'name' => 'submit',
            'options' => array(

            ),
            'attributes' => array(
                'type'  => 'submit',
            ),
        ));

        $nameInput = new Input('name');
        $shortCodeInput = new Input('short_code');

        $inputFilter = new InputFilter();

        $inputFilter->add($nameInput);
        $inputFilter->add($shortCodeInput);

        $this->setInputFilter($inputFilter);

    }

}