<?php

namespace Admin\Controller;

use Admin\Model\Course;
use Admin\Form\CourseForm;
use Admin\Service\StatusService;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class CourseController extends AbstractActionController
{

    protected $courseTable;
    protected $categoryTable;

    public function indexAction()
    {
        return new ViewModel(array(
                'courses' => $this->getDBTable()->fetchAll(),
        ));
    }

    public function addAction()
    {
        $categoryOptions = $this->getDBTable('Category')->getOpetionsForSelect('id', 'name');
        $form = new CourseForm($categoryOptions);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $course = new Course();
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $course->exchangeArray($form->getData());
                $this->getDBTable()->save($course);

                // Redirect to list of course
                return $this->redirect()->toRoute('course');
            }
        }
        return array('form' => $form);
    }

    public function editAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('course', array(
                        'action' => 'add'
            ));
        }

        // Get the Course with the specified id.  An exception is thrown
        // if it cannot be found, in which case go to the index page.
        try {
            $course = $this->getDBTable()->get($id);
        } catch (\Exception $ex) {
            return $this->redirect()->toRoute('course', array(
                        'action' => 'index'
            ));
        }

        $categoryOptions = $this->getDBTable('Category')->getOpetionsForSelect('id', 'name');
        $form = new CourseForm($categoryOptions);
        $form->bind($course);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setData($request->getPost());
            if ($form->isValid()) {
                $this->getDBTable()->save($course);
                return $this->redirect()->toRoute('course');
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

    public function deactivateAction()
    {
       $id = (int) $this->params()->fromRoute('id', 0);

       $statusService = new StatusService($this->getServiceLocator());
       $statusService->deactivateCourse($id);
       return $this->redirect()->toRoute('course', array(
           'action' => 'index'
       ));
    }

    public function activateAction()
    {
       $id = (int) $this->params()->fromRoute('id', 0);

       $statusService = new StatusService($this->getServiceLocator());
       $statusService->activateCourse($id);
       return $this->redirect()->toRoute('course', array(
           'action' => 'index'
       ));
    }

    /**
     *
     * @param type $tablePrefix
     * @return CourseTable|CategoryTable
     */
    public function getDBTable($tablePrefix = 'Course')
    {
        $defaultTable = $this->courseTable;
        if ($tablePrefix == 'Category') {
            $defaultTable = $this->categoryTable;
        }

        if (!$defaultTable) {
            $sm = $this->getServiceLocator();
            $defaultTable = $sm->get("Admin\Model\\{$tablePrefix}Table");
        }
        return $defaultTable;
    }

}
