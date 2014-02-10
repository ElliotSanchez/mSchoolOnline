<?php

namespace Admin\Form\Upload;

use Zend\Form\Element;
use Zend\Form\Form;
use Zend\InputFilter\FileInput;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\Input;
use Zend\Validator;

class Pathways extends Form
{

    public function __construct() {

        parent::__construct('pathways-upload-file');

        $this->add(array(
            'name' => 'submit',
            'options' => array(

            ),
            'attributes' => array(
                'type'  => 'submit',
            ),
        ));

        // File Input
        $file = new Element\File('pathways-file');
        $file->setAttribute('id', 'pathways-file');
        $this->add($file);

        // INPUT FILTERS
        $fileInput = new FileInput('pathways-file');
        $submitInput = new Input('submit');

        $fileInput->setRequired(true);
        $submitInput->setRequired(false);

        $inputFilter = new InputFilter();

        $inputFilter->add($fileInput);
        $inputFilter->add($submitInput);

        $this->setInputFilter($inputFilter);


    }

}