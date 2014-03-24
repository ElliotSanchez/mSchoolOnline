<?php

namespace Admin\Form\Pathway;

use Zend\Form\Form;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\Input;
use Zend\Validator AS ZendValidator;
use Admin\Plan\Service as PlanService;
use Admin\Pathway\Entity as Pathway;

class PlanAdd extends Form
{

    protected $planService;
    //protected $pathway;

    public function __construct(PlanService $planService) {

        $this->planService = $planService;

        parent::__construct('pathwayplan-edit');

        $this->add(array(
            'name' => 'submit',
            'options' => array(

            ),
            'attributes' => array(
                'type'  => 'submit',
            ),
        ));

        //$this->pathway = $pathway;

        $planOptions = [];

        foreach ($this->planService->all() as $plan) {
            $planOptions[$plan->id] = $plan->name  . ' (' . $plan->shortCode . ')';
        }

        $this->add(array(
            'name' => 'plan_id',
            'options' => array(
            ),
            'type'  => 'Select',
        ));

        $this->get('plan_id')->setValueOptions($planOptions);



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