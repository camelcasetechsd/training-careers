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

     public function fetchAll($order = array())
     {
        $select = $this->tableGateway->getSql()->select();
        if (!empty($order)) {
            $select->order($order);
        }

        $resultSet = $this->tableGateway->selectWith($select);

        return $resultSet;
     }

     public function get($id, $where = array())
     {
         $data = array();
         $id   = (int) $id;
         
         if (!empty($id)) {
            $rowset = $this->tableGateway->select(array('id' => $id));
            $data = $rowset->current();
         } else if (!empty($where)) {
            $select = $this->tableGateway->getSql()->select();
            $select->where($where);
            $rowset = $this->tableGateway->selectWith($select);
            foreach($rowset as $row) {
                $data[] = $row;
            }
         }
         
         if (!$data) {
             throw new \Exception("Could not find data");
         }
         return $data;
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

     public function delete($id, $where = array())
     {
        if (!empty($where)) {
           $this->tableGateway->delete($where);
        } else {
           $this->tableGateway->delete(array('id' => (int) $id));
        }
     }
 }