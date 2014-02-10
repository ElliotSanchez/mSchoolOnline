<?php

namespace Admin\Form\Upload;

use Zend\Form\Element;
use Zend\Form\Form;
use Zend\InputFilter\FileInput;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\Input;
use Zend\Validator;

class Students extends Form
{

    public function __construct() {

        parent::__construct('students-upload-file');

        $this->add(array(
            'name' => 'submit',
            'options' => array(

            ),
            'attributes' => array(
                'type'  => 'submit',
            ),
        ));

        // File Input
        $file = new Element\File('students-file');
        $file->setAttribute('id', 'students-file');
        $this->add($file);

        // INPUT FILTERS
        $fileInput = new FileInput('students-file');
        $submitInput = new Input('submit');

        $fileInput->setRequired(true);
        $submitInput->setRequired(false);

        $inputFilter = new InputFilter();

        $inputFilter->add($fileInput);
        $inputFilter->add($submitInput);

        $this->setInputFilter($inputFilter);


    }

}