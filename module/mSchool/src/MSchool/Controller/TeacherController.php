<?php

namespace MSchool\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;

class TeacherController extends AbstractActionController
{
    public function dashboardAction()
    {

        $this->layout('mschool/layout/layout');

        $session = $this->getServiceLocator()->get('StudentSessionContainer');

        return new ViewModel(array(
        ));

    }

}
