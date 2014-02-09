<?php

namespace MSchool\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class AuthController extends AbstractActionController
{
    public function loginAction()
    {
        die('mschool:auth:login');

//        $form = $this->getServiceLocator()->get('AdminLoginForm');
//
//        $this->layout('layout/auth');
//
//        if ($this->request->isPost()) {
//
//            $form->prepare();
//
//            $form->setData($this->request->getPost());
//
//            if ($form->isValid()) {
//
//                $inputFilter = $form->getInputFilter();
//
//                $username = $inputFilter->getRawValue('username');
//                $password = $inputFilter->getRawValue('password');
//
//                $adminAuthService = $this->getServiceLocator()->get('AdminAuthService');
//
//                $user = $adminAuthService->authenticate($username, $password);
//
//                $authService = $adminAuthService->getZendAuthService();
//
//                if ($authService->hasIdentity()) {
//                    return $this->redirect()->toUrl('dashboard');
//                } else {
//                    $this->flashMessenger()->addErrorMessage('Incorrect username or password');
//                    return $this->redirect()->toRoute('admin/login');
//                }
//
//            } else {
//                $this->flashMessenger()->addErrorMessage('Please enter both username and password');
//                foreach($form->getMessages() as $message) {
//                    $this->flashMessenger()->addErrorMessage($message);
//                }
//                return $this->redirect()->toRoute('admin\login');
//            }
//        }
//
//        return new ViewModel(array(
//            'form' => $form,
//        ));
    }

    public function logoutAction() {
        $authService = new \Zend\Authentication\AuthenticationService();

        $authService->clearIdentity();

        return $this->redirect()->toRoute('mschool/login');
    }
}
