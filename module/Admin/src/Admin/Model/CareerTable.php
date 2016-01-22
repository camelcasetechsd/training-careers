<?php
 namespace Admin\Model;

 use Zend\Db\TableGateway\TableGateway;

 class CareerTable extends AdminAbstractTable
 {
     public function save(Career $career)
     {
         $data = array(
             'name' => $career->name,
             'total_duration'  => $career->total_duration,
         );
         
         parent::save($career, $data);
     }
 }