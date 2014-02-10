<?php

namespace MSchool\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class PagesController extends AbstractActionController
{
    public function introAction()
    {
        return new ViewModel();
    }

    public function activityAction()
    {
        return new ViewModel();
    }

    public function progressAction()
    {
        return new ViewModel();
    }
}
