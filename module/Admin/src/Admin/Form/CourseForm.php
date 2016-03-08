<?php

 namespace Admin\Form;

 use Zend\Form\Form;

 class CourseForm extends Form
 {
     public function __construct($categoryOptions, $name = null)
     {
        // we want to ignore the name passed
        parent::__construct('course');

        $this->add(array(
            'name' => 'id',
            'type' => 'Hidden',
        ));
         $this->add(array(
             'name' => 'status',
             'type' => 'Hidden',
         ));
        $this->add(array(
            'name' => 'name',
            'type' => 'Text',
            'options' => array(
                'label' => 'Name',
            ),
        ));
        $this->add(array(
            'name' => 'code',
            'type' => 'Text',
            'options' => array(
                'label' => 'Code',
            ),
        ));
        $this->add(array(
            'name' => 'category_id',
            'type' => 'Select',
            'options' => array(
                'label' => 'Category',
                'empty_option' => '--Select--',
                'value_options' => $categoryOptions,
            ),
        ));
        $this->add(array(
            'name' => 'submit',
            'type' => 'Submit',
            'attributes' => array(
                'value' => 'Save',
                'id' => 'submitbutton',
            ),
        ));
     }
 }
