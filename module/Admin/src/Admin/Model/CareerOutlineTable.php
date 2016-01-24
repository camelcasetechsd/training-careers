<?php
 namespace Admin\Model;

 use Zend\Db\TableGateway\TableGateway;

 class CareerOutlineTable extends AdminAbstractTable
 {
     public function save(CareerOutline $careerOutline, $keys = NULL)
     {
         if (empty($keys)) {
            $data = array(
                'career_id' => $careerOutline->career_id,
                'outline_id' => $careerOutline->default_name,
            );
         } else {
            foreach ($keys as $key) {
                $data[$key] = $careerOutline->$key;
            }
        }
         parent::save($careerOutline, $data);
     }
 }