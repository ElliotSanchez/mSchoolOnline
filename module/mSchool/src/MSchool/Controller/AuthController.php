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

        $hasAccount = \MSchool\Module::account();

        if ($this->request->isPost()) {

            if (!$hasAccount) {
                $this->flashMessenger()->addErrorMessage('This account is not configured yet.');
                return $this->redirect()->toRoute('mschool/login');
            }

            $form->prepare();

            $form->setData($this->request->getPost());

            if ($form->isValid()) {

                $inputFilter = $form->getInputFilter();

                $username = $inputFilter->getRawValue('username');
                $password = $inputFilter->getRawValue('password');

                $studentAuthService = $this->getServiceLocator()->get('StudentAuthService');

                $user = $studentAuthService->authenticate($username, $password);

                $authService = $studentAuthService->getZendAuthService();

                if ($authService->hasIdentity()) {
                    return $this->redirect()->toRoute('mschool/home');
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
            'hasAccount' => $hasAccount,
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
                    return $this->redirect()->toRoute('mschool/teacher_dashboard');
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

        // DESTORY CONTAINER
        $session = $this->getServiceLocator()->get('StudentSessionContainer');
        $session->pathwayContainer = null;

        if ($user instanceof \Admin\Teacher\Entity) {
            return $this->redirect()->toRoute('mschool/teacher_login');
        } else {
            return $this->redirect()->toRoute('mschool/login');
        }

    }
}
