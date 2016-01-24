<?php
 namespace Admin\Model;

 use Zend\Db\TableGateway\TableGateway;

 class OutlineTable extends AdminAbstractTable
 {
     public function save(Outline $outline, $keys = NULL)
     {
         if (empty($keys)) {
            $data = array(
                'name' => $outline->name,
                'default_name' => $outline->default_name,
                'duration' => $outline->duration,
                'default_duration' => $outline->default_duration,
                'course_id' => $outline->course_id,
                'skill_id' => $outline->skill_id,
                'status' => $outline->status,
            );
         } else {
            foreach ($keys as $key) {
                $data[$key] = $outline->$key;
            }
        }
         parent::save($outline, $data);
     }
 }