<?php

namespace Admin\Form\Plan;

use Zend\Form\Form;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\Input;
use Zend\Validator AS ZendValidator;
use Admin\PlanStep\Entity as PlanStep;

class StepRemove extends Form
{

    public function __construct() {

        parent::__construct('planstep-remove');

        $this->add(array(
            'name' => 'planstep_id',
            'options' => array(

            ),
            'attributes' => array(
                'type'  => 'hidden',
            ),
        ));

        $this->add(array(
            'name' => 'submit',
            'options' => array(

            ),
            'attributes' => array(
                'type'  => 'submit',
            ),
        ));

    }

    public function setPlanStep(PlanStep $planStep) {
        $this->get('planstep_id')->setValue($planStep->id);
    }

}