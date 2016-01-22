<?php

 namespace Admin\Controller;

 use Admin\Model\Skill;
 use Admin\Form\SkillForm;

 use Zend\Mvc\Controller\AbstractActionController;
 use Zend\View\Model\ViewModel;

 class SkillController extends AbstractActionController
 {
     protected $skillTable;
     
     public function indexAction()
     {
        return new ViewModel(array(
            'categories' => $this->getSkillTable()->fetchAll(),
        ));
     }

     public function addAction()
     {
        $form = new SkillForm();
        
        $request = $this->getRequest();
        if ($request->isPost()) {
            $skill = new Skill();
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $skill->exchangeArray($form->getData());
                $this->getSkillTable()->save($skill);

                // Redirect to list of skill
                return $this->redirect()->toRoute('skill');
            }
        }
        return array('form' => $form);
     }

     public function editAction()
     {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('skill', array(
                'action' => 'add'
            ));
        }

        // Get the Skill with the specified id.  An exception is thrown
        // if it cannot be found, in which case go to the index page.
        try {
            $skill = $this->getSkillTable()->get($id);
        }
        catch (\Exception $ex) {
            return $this->redirect()->toRoute('skill', array(
                'action' => 'index'
            ));
        }

        $form  = new SkillForm();
        $form->bind($skill);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setData($request->getPost());
            if ($form->isValid()) {
                $this->getSkillTable()->save($skill);
                return $this->redirect()->toRoute('skill');
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
    
     public function getSkillTable()
     {
        if (!$this->skillTable) {
            $sm = $this->getServiceLocator();
            $this->skillTable = $sm->get('Admin\Model\SkillTable');
        }
        return $this->skillTable;
     }
 }