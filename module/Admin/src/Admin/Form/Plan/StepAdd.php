<?php

namespace Admin\Form\Plan;

use Zend\Form\Form;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\Input;
use Zend\Validator AS ZendValidator;
use Admin\Step\Service as StepService;
use Admin\Plan\Entity as Plan;

class StepAdd extends Form
{

    protected $stepService;
    //protected $plan;

    public function __construct(StepService $stepService) {

        $this->stepService = $stepService;

        parent::__construct('planstep-edit');

        $this->add(array(
            'name' => 'submit',
            'options' => array(

            ),
            'attributes' => array(
                'type'  => 'submit',
            ),
        ));

        //$this->plan = $plan;

        $stepOptions = [];

        foreach ($this->stepService->all() as $step) {
            $stepOptions[$step->id] = $step->name  . ' (' . $step->shortCode . ')';
        }

        $this->add(array(
            'name' => 'step_id',
            'options' => array(
            ),
            'type'  => 'Select',
        ));

        $this->get('step_id')->setValueOptions($stepOptions);



//        $nameInput = new Input('name');
//        $shortCodeInput = new Input('short_code');
//        $isActive = new Input('is_active');
//
//        $inputFilter = new InputFilter();
//
//        $inputFilter->add($nameInput);
//        $inputFilter->add($shortCodeInput);
//        $inputFilter->add($isActive);
//
//        $this->setInputFilter($inputFilter);

    }

}