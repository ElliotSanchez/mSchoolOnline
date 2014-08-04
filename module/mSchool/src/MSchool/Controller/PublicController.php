<?php

namespace MSchool\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class PublicController extends AbstractActionController
{
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
}
