<?php

 namespace Admin\Model;

 class Career implements AdminModelInterface
 {
     public $id;
     public $name;
     public $status;
     public $total_duration;

     public function exchangeArray($data)
     {
         $this->id              = (!empty($data['id'])) ? $data['id'] : null;
         $this->name            = (!empty($data['name'])) ? $data['name'] : null;
         $this->status          = (!empty($data['status'])) ? $data['status'] : 0;
         $this->total_duration  = (!empty($data['total_duration']) || $data['total_duration'] === '0') ? $data['total_duration'] : null;
     }

    public function getArrayCopy()
    {
        return get_object_vars($this);
    }
 }
