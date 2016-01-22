<?php
 namespace Admin\Model;

 use Zend\Db\TableGateway\TableGateway;

 class SkillTable extends AdminAbstractTable
 {
     public function save(Skill $category)
     {
         $data = array(
             'name' => $category->name,
             'code' => $category->code,
         );
         parent::save($category, $data);
     }
 }