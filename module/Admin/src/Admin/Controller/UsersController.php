<?php

namespace Admin\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class UsersController extends AbstractActionController
{
    public function indexAction()
    {
        return new ViewModel(array(
            'users' => $this->getServiceLocator()->get('UserService')->all(),
        ));
    }

    public function addAction()
    {

        $form = $this->getServiceLocator()->get('UserAddForm');

        $request = $this->getRequest();

        if ($request->isPost()) {

            $form->setData($request->getPost());

            if ($form->isValid()) {
                $data = $form->getData();

                $user = $this->getServiceLocator()->get('UserService')->create($data);

                $this->flashMessenger()->addSuccessMessage('Added User');
                $this->redirect()->toRoute('admin/user_edit', array('id' => $user->id));
            }

        }

        return new ViewModel([
            'form' => $form,
        ]);

    }

    public function editAction() {

        $userService = $this->getServiceLocator()->get('UserService');

        $user = $userService->get($this->params('id'));

        $form = $this->getServiceLocator()->get('UserEditForm');

        $form->bind($user);

        $request = $this->getRequest();

        if ($request->isPost()) {

            $form->setData($request->getPost());

            if ($form->isValid()) {

                $user = $form->getData();

                $password = $form->getInputFilter()->getRawValue('password');
                if (strlen($password))
                    $user->setPassword($password);

                $userService->save($user);

                $this->flashMessenger()->addSuccessMessage('Updated User');
                $this->redirect()->toRoute('admin/user_edit', array('id' => $user->id));
            }

        }

        return new ViewModel([
            'form' => $form,
        ]);

    }

}
