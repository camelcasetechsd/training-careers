<?php

 namespace Admin\Model;

 class Course implements AdminModelInterface
 {
     public $id;
     public $name;
     public $code;
     public $category_id;

    public function exchangeArray($data)
    {
        $this->id     = (!empty($data['id'])) ? $data['id'] : null;
        $this->name = (!empty($data['name'])) ? $data['name'] : null;
        $this->code = (!empty($data['code'])) ? $data['code'] : null;
        $this->category_id  = (!empty($data['category_id'])) ? $data['category_id'] : null;
    }

    public function getArrayCopy()
    {
        return get_object_vars($this);
    }
 }