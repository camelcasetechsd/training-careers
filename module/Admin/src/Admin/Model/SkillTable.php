<?php
 namespace Admin\Model;

 use Zend\Db\TableGateway\TableGateway;

 class SkillTable extends AdminAbstractTable
 {
     public function save(Skill $skill, $keys = NULL)
     {
         if (empty($keys)) {
            $data = array(
                'name' => $skill->name,
                'code' => $skill->code,
                'status' => $skill->status,
            );
         } else {
            foreach ($keys as $key) {
                $data[$key] = $skill->$key;
            }
        }
         parent::save($skill, $data);
     }
 }