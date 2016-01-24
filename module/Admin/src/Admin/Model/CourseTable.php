<?php
 namespace Admin\Model;

 use Zend\Db\TableGateway\TableGateway;

 class CourseTable extends AdminAbstractTable
 {
     public function save(Course $course, $keys = NULL)
     {
        if (empty($keys)) {
            $data = array(
                'name' => $course->name,
                'code' => $course->code,
                'category_id'  => $course->category_id,
                'status'  => $course->status,
            );
        } else {
            foreach ($keys as $key) {
                $data[$key] = $course->$key;
            }
        }
        parent::save($course, $data);
     }
 }