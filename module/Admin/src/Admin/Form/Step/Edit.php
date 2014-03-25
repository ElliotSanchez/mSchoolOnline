<?php

namespace Admin\Form\Step;

use Zend\Form\Form;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\Input;
use Zend\Validator AS ZendValidator;
use Admin\Resource\Service as ResourceService;

class Edit extends Form
{

    public function __construct(ResourceService $resourceService) {

        parent::__construct('step-edit');

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
            'name' => 'timer',
            'options' => array(

            ),
            'type'  => 'Text',
        ));

        // RESOURCE
        $resourceOptions = array();

        foreach($resourceService->all() as $resource) {
            $resourceOptions[$resource->id] = $resource->shortCode;
        }

        $this->add(array(
            'name' => 'resource_id',
            'options' => array(
                'empty_option' => 'Select Resource',
                'value_options' => $resourceOptions,
            ),
            'type' => 'Select',
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