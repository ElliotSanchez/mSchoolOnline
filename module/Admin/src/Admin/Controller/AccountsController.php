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

        $account = $this->getAccount();

        $schools = $this->getServiceLocator()->get('SchoolService')->getForAccount($account);

        return new ViewModel(array(
            'schools' => $schools,
            'account' => $account,
        ));

    }

    public function teachersAction() {
        return new ViewModel([
            'account' => $this->getAccount(),
        ]);
    }

    public function studentsAction() {
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
            'account' => $account,
            'form' => $form,
        ]);

    }

    public function schoolAddAction()
    {
        $account = $this->getAccount();

        $form = $this->getServiceLocator()->get('SchoolAddForm');

        $request = $this->getRequest();

        if ($request->isPost()) {

            $form->setData($request->getPost());

            if ($form->isValid()) {
                $data = $form->getData();
                $data['account_id'] = $account->id;
                $school = $this->getServiceLocator()->get('SchoolService')->create($data);
                $this->flashMessenger()->addSuccessMessage('Added School');
                $this->redirect()->toRoute('admin/school_edit', array('a_id' => $account->id, 's_id' => $school->id));
            }

        }

        return new ViewModel([
            'account' => $account,
            'form' => $form,
        ]);

    }

    public function schoolEditAction()
    {

        $accountService = $this->getServiceLocator()->get('AccountService');
        $schoolService = $this->getServiceLocator()->get('SchoolService');

        $account = $accountService->get($this->params('a_id'));
        $school = $schoolService->get($this->params('s_id'));

        $form = $this->getServiceLocator()->get('SchoolEditForm');

        $form->bind($school);

        $request = $this->getRequest();

        if ($request->isPost()) {

            $form->setData($request->getPost());

            if ($form->isValid()) {

                $school = $form->getData();

                $school->accountId = $account->id; // TODO FIX THIS

                $schoolService->save($school);

                $this->flashMessenger()->addSuccessMessage('Updated School');
                $this->redirect()->toRoute('admin/school_edit', array('a_id' => $account->id, 's_id' => $school->id));
            }

        }

        return new ViewModel([
            'account' => $account,
            'form' => $form,
        ]);

    }

    protected function getAccount() {

        $accountService = $this->getServiceLocator()->get('AccountService');

        return $accountService->get($this->params('id'));

    }

}
