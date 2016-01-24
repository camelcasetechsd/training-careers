<?php
 namespace Admin\Model;

 use Zend\Db\TableGateway\TableGateway;

 class CategoryTable extends AdminAbstractTable
 {
    public function save(Category $category, $keys = NULL)
    {
        if (empty($keys)) {
            $data = array(
                'name' => $category->name,
                'code' => $category->code,
                'default' => $category->default,
                'status' => $category->status,
            );
        } else {
            foreach ($keys as $key) {
                $data[$key] = $category->$key;
            }
        }
        
        parent::save($category, $data);
    }
    
    public function fetchAll()
    {
        $order = array('default DESC', 'name ASC');
        return parent::fetchAll($order);
    }

 }