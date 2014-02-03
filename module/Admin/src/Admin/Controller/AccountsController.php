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
}
