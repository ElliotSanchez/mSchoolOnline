<?php

namespace MSchool\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;

class TeacherController extends AbstractActionController
{
    public function dashboardAction()
    {

        $teacher = $adminAuthService = $this->getServiceLocator()->get('TeacherAuthService')->getCurrentUser();

        $mclasses = $this->getServiceLocator()->get('MclassService')->getMclassesForTeacher($teacher);

        $this->layout('mschool/layout/coach');

        return new ViewModel(array(
            'mclasses' => $mclasses,
        ));

    }

    public function classDashboardAction()
    {

        $this->layout('mschool/layout/coach');

        $mclass = $this->getServiceLocator()->get('MclassService')->get($this->params('id'));
        $students = $this->getServiceLocator()->get('MclassService')->getStudentsAssignedToMclass($mclass);

        return new ViewModel(array(
            'mclass' => $mclass,
            'students' => $students,
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

    public function classProgressAction()
    {

        $this->layout('mschool/layout/layout');

    }

    public function studentProgressAction()
    {

        $student = $this->getServiceLocator()->get('StudentService')->get($this->params('s_id'));
        $mclass = $this->getServiceLocator()->get('MclassService')->get($this->params('m_id'));

        $this->layout('mschool/layout/coach');

        return new ViewModel([
            'student' => $student,
            'mclass' => $mclass,
        ]);
    }

}
