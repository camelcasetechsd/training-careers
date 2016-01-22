<?php

 namespace Admin\Form;

 use Zend\Form\Form;

 class CategoryForm extends Form
 {
     public function __construct($name = null)
     {
         // we want to ignore the name passed
         parent::__construct('category');

         $this->add(array(
             'name' => 'id',
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
             'name' => 'submit',
             'type' => 'Submit',
             'attributes' => array(
                 'value' => 'Save',
                 'id' => 'submitbutton',
             ),
         ));
     }
 }