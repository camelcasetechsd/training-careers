<?php
 namespace Admin\Model;

 use Zend\Db\TableGateway\TableGateway;

 class CourseTable extends AdminAbstractTable
 {
     public function save(Course $course)
     {
         $data = array(
             'name' => $course->name,
             'code' => $course->code,
             'category_id'  => $course->category_id,
         );
         parent::save($course, $data);
     }
 }