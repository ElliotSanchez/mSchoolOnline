<?php

namespace Admin\Form\Resources;

use Zend\Form\Form;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\Input;
use Zend\Validator AS ZendValidator;

class Add extends Form
{
    protected $schoolService;

    protected $account;

    public function __construct() {

        parent::__construct('resource-add');

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
            'name' => 'url',
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
        $urlInput = new Input('url');

        $nameInput->setAllowEmpty(true);

        //$urlInput->getValidatorChain()->attach(new ZendValidator\Hostname(ZendValidator\Hostname::ALLOW_URI));

        $inputFilter = new InputFilter();

        $inputFilter->add($nameInput);
        $inputFilter->add($shortCodeInput);
        $inputFilter->add($urlInput);

        $this->setInputFilter($inputFilter);

    }

}