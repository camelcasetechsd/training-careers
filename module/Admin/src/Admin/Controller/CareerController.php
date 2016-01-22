<?php

 namespace Admin\Controller;

 use Admin\Model\Career;
 use Admin\Form\CareerForm;

 use Zend\Mvc\Controller\AbstractActionController;
 use Zend\View\Model\ViewModel;

 class CareerController extends AbstractActionController
 {
     protected $careerTable;
     
     public function indexAction()
     {
        return new ViewModel(array(
            'careers' => $this->getCareerTable()->fetchAll(),
        ));
     }

     public function addAction()
     {
        $form = new CareerForm();

        $request = $this->getRequest();
        if ($request->isPost()) {
            $career = new Career();

            $form->setData($request->getPost());

            if ($form->isValid()) {
                
                $career->exchangeArray($form->getData());
                $this->getCareerTable()->save($career);

                // Redirect to list of career
                return $this->redirect()->toRoute('career');
            }
        }
        return array('form' => $form);
     }

     public function editAction()
     {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('career', array(
                'action' => 'add'
            ));
        }

        // Get the Career with the specified id.  An exception is thrown
        // if it cannot be found, in which case go to the index page.
        try {
            $career = $this->getCareerTable()->get($id);
        }
        catch (\Exception $ex) {
            return $this->redirect()->toRoute('career', array(
                'action' => 'index'
            ));
        }

        $form  = new CareerForm();
        $form->bind($career);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setData($request->getPost());
            if ($form->isValid()) {
                $this->getCareerTable()->save($career);
                return $this->redirect()->toRoute('career');
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
    
     public function getCareerTable()
     {
         if (!$this->careerTable) {
             $sm = $this->getServiceLocator();
             $this->careerTable = $sm->get('Admin\Model\CareerTable');
         }
         return $this->careerTable;
     }
 }