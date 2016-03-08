<?php

 namespace Admin\Model;

 class Outline implements AdminModelInterface
 {
     public $id;
     public $name;
     public $default_name;
     public $duration;
     public $default_duration;
     public $course_id;
     public $skill_id;
     public $status;

     public function exchangeArray($data)
     {
         $this->id               = (!empty($data['id'])) ? $data['id'] : null;
         $this->name             = (!empty($data['name'])) ? $data['name'] : null;
         $this->default_name     = (!empty($data['default_name'])) ? $data['default_name'] : null;
         $this->duration         = (!empty($data['duration'])) ? $data['duration'] : null;
         $this->default_duration = (!empty($data['default_duration'])) ? $data['default_duration'] : null;
         $this->course_id        = (!empty($data['course_id'])) ? $data['course_id'] : null;
         $this->skill_id         = (!empty($data['skill_id'])) ? $data['skill_id'] : null;
         $this->status           = (!empty($data['status'])) ? $data['status'] : 0;
     }
     
    public function getArrayCopy()
    {
        return get_object_vars($this);
    }
 }
