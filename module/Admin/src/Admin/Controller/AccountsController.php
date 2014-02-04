<?php

namespace Admin\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class AccountsController extends AbstractActionController
{
    public function indexAction()
    {

        return new ViewModel(array(
            'accounts' => $this->getServiceLocator()->get('AccountService')->all(),
        ));
    }

    public function viewAction()
    {
        return new ViewModel([
            'account' => $this->getAccount(),
        ]);
    }

    public function schoolsAction() {
        return new ViewModel([
            'account' => $this->getAccount(),
        ]);
    }

    public function usersAction() {
        return new ViewModel([
            'account' => $this->getAccount(),
        ]);
    }

    public function addAction()
    {

        $form = $this->getServiceLocator()->get('AccountAddForm');

        $request = $this->getRequest();

        if ($request->isPost()) {

            $form->setData($request->getPost());

            if ($form->isValid()) {
                $account = $this->getServiceLocator()->get('AccountService')->create($form->getData());
                $this->flashMessenger()->addSuccessMessage('Added Account');
                $this->redirect()->toUrl('/account/'.$account->id);
            }

        }

        return new ViewModel([
            'form' => $form,
        ]);

    }

    public function editAction()
    {

        $accountService = $this->getServiceLocator()->get('AccountService');

        $account = $accountService->get($this->params('id'));

        $form = $this->getServiceLocator()->get('AccountEditForm');

        $form->bind($account);

        $request = $this->getRequest();

        if ($request->isPost()) {

            $form->setData($request->getPost());

            if ($form->isValid()) {

                $account = $form->getData();
                $accountService->save($account);

                $this->flashMessenger()->addSuccessMessage('Updated Account');
                $this->redirect()->toUrl('/account/'.$account->id);
            }

        }

        return new ViewModel([
            'form' => $form,
        ]);

    }

    protected function getAccount() {

        $accountService = $this->getServiceLocator()->get('AccountService');

        return $accountService->get($this->params('id'));

    }

}
