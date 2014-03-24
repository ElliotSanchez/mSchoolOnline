<?php

namespace Admin\Form\Pathway;

use Zend\Form\Form;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\Input;
use Zend\Validator AS ZendValidator;
use Admin\PathwayPlan\Entity as PathwayPlan;

class PlanRemove extends Form
{

    public function __construct() {

        parent::__construct('pathwayplan-remove');

        $this->add(array(
            'name' => 'pathwayplan_id',
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

    public function setPathwayPlan(PathwayPlan $pathwayPlan) {
        $this->get('pathwayplan_id')->setValue($pathwayPlan->id);
    }

}