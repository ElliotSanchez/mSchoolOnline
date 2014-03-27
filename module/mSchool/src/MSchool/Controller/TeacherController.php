<?php

namespace MSchool\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;

class TeacherController extends AbstractActionController
{
    public function dashboardAction()
    {

        $this->layout('mschool/layout/layout');

        return new ViewModel(array(
        ));

    }

    public function studentsAction()
    {

        $this->layout('mschool/layout/layout');

        $studentService = $this->getServiceLocator()->get('StudentService');

        $class = null;

        $students = $studentService->getStudentsInClass($class);

        return new ViewModel(array(

        ));

    }

}