<?php

 namespace Admin\Model;

 class CareerOutline implements AdminModelInterface
 {
     public $id;
     public $career_id;
     public $outline_id;

     public function exchangeArray($data)
     {
         $this->id          = (!empty($data['id'])) ? $data['id'] : null;
         $this->career_id   = (!empty($data['career_id'])) ? $data['career_id'] : null;
         $this->outline_id  = (!empty($data['outline_id'])) ? $data['outline_id'] : null;
     }
     
    public function getArrayCopy()
    {
        return get_object_vars($this);
    }
 }