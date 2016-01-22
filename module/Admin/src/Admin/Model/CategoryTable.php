<?php
 namespace Admin\Model;

 use Zend\Db\TableGateway\TableGateway;

 class CategoryTable extends AdminAbstractTable
 {
     public function save(Category $category)
     {
         $data = array(
             'name' => $category->name,
             'code' => $category->code,
         );
         parent::save($category, $data);
     }
 }