<?php

namespace MSchool\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class PublicController extends AbstractActionController
{
    public function indexAction() {
        $this->redirect()->toUrl('http://mschools.org');
    }

    public function termsAction() {
        $this->layout('layout/public');
    }

    public function coachSignupAction()
    {

        $form = $this->getServiceLocator()->get('CoachSignupForm');

        $this->layout('layout/public');

        $signupService = $this->getServiceLocator()->get('CoachSignupService');

        if ($this->request->isPost()) {

            $form->prepare();

            $form->setData($this->request->getPost());

            if ($form->isValid()) {
                $signupService->signup($form->getData());

                return $this->redirect()->toRoute('public/coach_signup_complete');

            } else {
                foreach($form->getMessages() as $message) {
                    $this->flashMessenger()->addErrorMessage($message);
                }
                return $this->redirect()->toRoute('public/coach_signup');
            }
        }

        return new ViewModel(array(
            'form' => $form,
        ));

    }

    public function coachSignupCompleteAction() {
        $this->layout('layout/public');
    }

    public function coachSignupConfirmationAction() {

        $this->layout('layout/public');

        $coachSignup = $this->getServiceLocator()->get('CoachSignupService')->findWithConfirmationKey($this->params('confirmation_key'));

        $accountService = $this->getServiceLocator()->get('AccountService');
        $coachSignupService = $this->getServiceLocator()->get('CoachSignupService');

        if ($coachSignup) {

            $coachSignupService->confirmCoachSignup($coachSignup);

            $this->flashMessenger()->addSuccessMessage('Account confirmed');
            $defaultAccount = $accountService->getDefaultAccount();
            return $this->redirect()->toRoute('mschool/teacher_login', ['subdomain' => $defaultAccount->subdomain]);

        }

        return new ViewModel([

        ]);

    }
}
