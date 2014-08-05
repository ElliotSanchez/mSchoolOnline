<?php

namespace MSchool\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;
use Admin\Teacher\Entity as Teacher;
use Zend\Stdlib\DispatchableInterface;
use Zend\Stdlib\RequestInterface as Request;
use Zend\Stdlib\ResponseInterface as Response;

class TeacherController extends AbstractActionController implements DispatchableInterface
{
    protected $teacher;
    protected $assignedMclasses;
    protected $mclass;

    public function dispatch(Request $request, Response $response = null)
    {
        $this->teacher = $this->getServiceLocator()->get('TeacherAuthService')->getCurrentUser();
        $this->loadAssignedMclasses();
        $this->loadRequestedMclass();

        return parent::dispatch($request, $response);
    }

    protected function loadAssignedMclasses() {
        $this->assignedMclasses = $this->getServiceLocator()->get('MclassService')->getMclassesForTeacher($this->teacher);
    }

    protected function loadRequestedMclass() {
        $mclassId = $this->params('id');
        $mclass = null;

        if ($mclassId) {
            $this->mclass = $this->getServiceLocator()->get('MclassService')->get($mclassId);
        } else if (count($this->assignedMclasses)) {
            $this->mclass = $this->assignedMclasses[0];
        }

        return $this->mclass;
    }

    public function dashboardAction()
    {
        $teacher = $adminAuthService = $this->getServiceLocator()->get('TeacherAuthService')->getCurrentUser();

        $mclasses = $this->getServiceLocator()->get('MclassService')->getMclassesForTeacher($teacher);

        $this->layout('mschool/layout/coach');

        return new ViewModel(array(
            'mclasses' => $mclasses,
        ));
    }

    public function progressAction() {
        $this->layout('mschool/layout/coach');

        $studentsInClass = $this->getServiceLocator()->get('MclassService')->getStudentsAssignedToMclass($this->mclass);

        return new ViewModel(array(
            'mclasses' => $this->assignedMclasses,
            'mclass' => $this->mclass,
            'students' => $studentsInClass,
        ));
    }

    public function assessmentAction() {

        $this->layout('mschool/layout/coach');

        return new ViewModel(array(
            'mclasses' => $this->assignedMclasses,
            'mclass' => $this->mclass,
        ));

    }

    public function studentAccountsAction() {

        $teacher = $adminAuthService = $this->getServiceLocator()->get('TeacherAuthService')->getCurrentUser();
        $students = $this->getServiceLocator()->get('MclassService')->getStudentsAssignedToTeacher($teacher);

        $this->layout('mschool/layout/coach');

        return new ViewModel(array(
            'students' => $students,
            'account' => \MSchool\Module::account()
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

    public function studentPasswordAction() {

        if (\MSchool\Module::account()->isDefault) $this->redirect()->toRoute('mschool/teacher_student_accounts');

        $studentService = $this->getServiceLocator()->get('StudentService');

        $student = $studentService->get($this->params('s_id'));

        $this->layout('mschool/layout/coach');

        $form = new \MSchool\Form\StudentPassword();

        $request = $this->getRequest();

        if ($request->isPost()) {

            $form->setData($request->getPost());

            if ($form->isValid()) {

                $password = $form->getInputFilter()->getRawValue('password');

                if (strlen($password))
                    $student->setPassword($password);

                $studentService->save($student);

                $this->flashMessenger()->addSuccessMessage('Updated Student\'s Password');
                $this->redirect()->toRoute('mschool/teacher_student_password', array('id' => $student->id));
            }

        }

        return new ViewModel([
            'student' => $student,
            'form' => $form,
        ]);

    }
}
