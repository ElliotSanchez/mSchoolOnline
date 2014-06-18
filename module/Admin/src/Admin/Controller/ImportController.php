<?php

namespace Admin\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class ImportController extends AbstractActionController
{
    public function statusAction()
    {

        $dropbox = $this->getServiceLocator()->get('Dropbox');

        return new ViewModel([
           'dropbox' => $dropbox,
        ]);

    }

    public function importAction() {




    }

}
