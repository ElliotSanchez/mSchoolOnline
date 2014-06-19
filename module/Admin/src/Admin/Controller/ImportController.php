<?php

namespace Admin\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\View\View;

class ImportController extends AbstractActionController
{
    public function statusAction()
    {

        $dropbox = $this->getServiceLocator()->get('Dropbox');

        $this->layout()->activeMenu = 'import';

        return new ViewModel([
           'dropbox' => $dropbox,
        ]);

    }

    public function importAction() {

        $importer = $this->getServiceLocator()->get('Importer');
        $iReadyImporter = $this->getServiceLocator()->get('iReadyImporter');

        $dropbox = $this->getServiceLocator()->get('Dropbox');
        $studentService = $this->getServiceLocator()->get('StudentService');

        $fd = tmpfile();
        //$metadata = $dropbox->getFile($path, $fd);

        $importer->setDropbox($dropbox);
        $importer->setImporter($iReadyImporter);
        $importer->setStudentService($studentService);

        $importer->import();

        $this->redirect()->toUrl('/import/history/iready');

    }

    public function historyAction() {

        $type = $this->params('type');

        $iready = $this->getServiceLocator()->get('IreadyService')->all();

        $this->layout()->activeMenu = 'import';

        return new ViewModel([
            'iready' => $iready,
            'type' => $type,
        ]);

    }

}
