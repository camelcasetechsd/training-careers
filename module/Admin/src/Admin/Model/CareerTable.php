<?php
 namespace Admin\Model;

 use Zend\Db\TableGateway\TableGateway;

 class CareerTable extends AdminAbstractTable
 {
    public function save(Career $career, $keys = NULL)
    {
        if (empty($keys)) {
            $data = array(
                'name' => $career->name,
                'total_duration'  => $career->total_duration,
                'status' => $career->status,
            );
        } else {
            foreach ($keys as $key) {
                $data[$key] = $career->$key;
            }
        }

        parent::save($career, $data);
    }
 }