<?php

namespace MSchool\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class AuthController extends AbstractActionController
{
    public function loginAction()
    {

        $form = $this->getServiceLocator()->get('StudentLoginForm');

        $this->layout('layout/auth');

        if ($this->request->isPost()) {

            $form->prepare();

            $form->setData($this->request->getPost());

            if ($form->isValid()) {

                $inputFilter = $form->getInputFilter();

                $username = $inputFilter->getRawValue('username');
                $password = $inputFilter->getRawValue('password');

                $adminAuthService = $this->getServiceLocator()->get('StudentAuthService');

                $user = $adminAuthService->authenticate($username, $password);

                $authService = $adminAuthService->getZendAuthService();

                if ($authService->hasIdentity()) {
                    return $this->redirect()->toUrl('pathway');
                } else {
                    $this->flashMessenger()->addErrorMessage('Incorrect username or password');
                    return $this->redirect()->toRoute('mschool/login');
                }

            } else {
                $this->flashMessenger()->addErrorMessage('Please enter both username and password');
                foreach($form->getMessages() as $message) {
                    $this->flashMessenger()->addErrorMessage($message);
                }
                return $this->redirect()->toRoute('mschool/login');
            }
        }

        return new ViewModel(array(
            'form' => $form,
        ));
    }

    public function teacherLoginAction()
    {

        $form = $this->getServiceLocator()->get('TeacherLoginForm');

        $this->layout('layout/auth');

        if ($this->request->isPost()) {

            $form->prepare();

            $form->setData($this->request->getPost());

            if ($form->isValid()) {
                $inputFilter = $form->getInputFilter();

                $username = $inputFilter->getRawValue('username');
                $password = $inputFilter->getRawValue('password');

                $teacherAuthService = $this->getServiceLocator()->get('TeacherAuthService');

                $user = $teacherAuthService->authenticate($username, $password);

                $authService = $teacherAuthService->getZendAuthService();

                if ($authService->hasIdentity()) {
                    return $this->redirect()->toUrl('mschool/teacher_dashboard');
                } else {
                    $this->flashMessenger()->addErrorMessage('Incorrect username or password');
                    return $this->redirect()->toRoute('mschool/teacher_login');
                }

            } else {
                $this->flashMessenger()->addErrorMessage('Please enter both username and password');
                foreach($form->getMessages() as $message) {
                    $this->flashMessenger()->addErrorMessage($message);
                }
                return $this->redirect()->toRoute('mschool/teacher_login');
            }
        }

        return new ViewModel(array(
            'form' => $form,
        ));
    }

    public function logoutAction() {
        $authService = $this->getServiceLocator()->get('AdminAuthService');

        $user = $authService->getCurrentUser();

        $authService->logout();

        if ($user instanceof \Admin\Teacher\Entity) {
            return $this->redirect()->toRoute('mschool/teacher_login');
        } else {
            return $this->redirect()->toRoute('mschool/login');
        }

    }
}
