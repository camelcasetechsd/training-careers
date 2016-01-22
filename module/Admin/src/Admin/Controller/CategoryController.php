<?php

 namespace Admin\Controller;

 use Admin\Model\Category;
 use Admin\Form\CategoryForm;

 use Zend\Mvc\Controller\AbstractActionController;
 use Zend\View\Model\ViewModel;

 class CategoryController extends AbstractActionController
 {
     protected $categoryTable;
     
     public function indexAction()
     {
        return new ViewModel(array(
            'categories' => $this->getCategoryTable()->fetchAll(),
        ));
     }

     public function addAction()
     {
        $form = new CategoryForm();
        
        $request = $this->getRequest();
        if ($request->isPost()) {
            $category = new Category();
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $category->exchangeArray($form->getData());
                $this->getCategoryTable()->save($category);

                // Redirect to list of category
                return $this->redirect()->toRoute('category');
            }
        }
        return array('form' => $form);
     }

     public function editAction()
     {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('category', array(
                'action' => 'add'
            ));
        }

        // Get the Category with the specified id.  An exception is thrown
        // if it cannot be found, in which case go to the index page.
        try {
            $category = $this->getCategoryTable()->get($id);
        }
        catch (\Exception $ex) {
            return $this->redirect()->toRoute('category', array(
                'action' => 'index'
            ));
        }

        $form  = new CategoryForm();
        $form->bind($category);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setData($request->getPost());
            if ($form->isValid()) {
                $this->getCategoryTable()->save($category);
                return $this->redirect()->toRoute('category');
            }
        }

        return array(
            'id' => $id,
            'form' => $form,
        );
     }

     public function deleteAction()
     {
     }
    
     public function getCategoryTable()
     {
        if (!$this->categoryTable) {
            $sm = $this->getServiceLocator();
            $this->categoryTable = $sm->get('Admin\Model\CategoryTable');
        }
        return $this->categoryTable;
     }
 }