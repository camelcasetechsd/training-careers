<?php

 namespace Admin\Form;

 use Zend\Form\Form;

 class CareerForm extends Form
 {
     public function __construct($name = null)
     {
         // we want to ignore the name passed
         parent::__construct('career');

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
             'name' => 'total_duration',
             'type' => 'Text',
             'options' => array(
                 'label' => 'Total Duration',
             ),
            'attributes' => array(
                'readonly' => 'readonly',
                'value' => '0',
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
