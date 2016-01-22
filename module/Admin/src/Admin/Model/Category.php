<?php

 namespace Admin\Model;

 class Category implements AdminModelInterface
 {
     public $id;
     public $name;
     public $code;

     public function exchangeArray($data)
     {
         $this->id     = (!empty($data['id'])) ? $data['id'] : null;
         $this->name = (!empty($data['name'])) ? $data['name'] : null;
         $this->code = (!empty($data['code'])) ? $data['code'] : null;
     }
     
    public function getArrayCopy()
    {
        return get_object_vars($this);
    }
 }