<?php

 namespace Admin\Model;

 class Skill implements AdminModelInterface
 {
     public $id;
     public $name;
     public $code;
     public $status;

     public function exchangeArray($data)
     {
         $this->id     = (!empty($data['id'])) ? $data['id'] : null;
         $this->name   = (!empty($data['name'])) ? $data['name'] : null;
         $this->code   = (!empty($data['code'])) ? $data['code'] : null;
         $this->status = (!empty($data['status'])) ? $data['status'] : 0;
     }
     
    public function getArrayCopy()
    {
        return get_object_vars($this);
    }
 }
