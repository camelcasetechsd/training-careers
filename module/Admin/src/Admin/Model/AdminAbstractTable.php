<?php
 namespace Admin\Model;

 use Zend\Db\TableGateway\TableGateway;

 Abstract class AdminAbstractTable
 {
     protected $tableGateway;

     public function __construct(TableGateway $tableGateway)
     {
         $this->tableGateway = $tableGateway;
     }

     public function fetchAll()
     {
         $resultSet = $this->tableGateway->select();
         return $resultSet;
     }

     public function get($id)
     {
         $id  = (int) $id;
         $rowset = $this->tableGateway->select(array('id' => $id));
         $row = $rowset->current();
         if (!$row) {
             throw new \Exception("Could not find row $id");
         }
         return $row;
     }

     public function getOpetionsForSelect($valueField, $labelField)
     {
         $results = $this->fetchAll();
         $options = array();

         foreach ($results as $result) {
             $options[$result->$valueField] = $result->$labelField;
         }

         return $options;
     }
     protected function save(AdminModelInterface $model, $data)
     {
         $id = (int) $model->id;
         if ($id == 0) {
             $this->tableGateway->insert($data);
         } else {
             if ($this->get($id)) {
                 $this->tableGateway->update($data, array('id' => $id));
             } else {
                 throw new \Exception('record id does not exist');
             }
         }
     }

     protected function delete($id)
     {
         $this->tableGateway->delete(array('id' => (int) $id));
     }
 }