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

        $type = $this->params('type');

        switch ($type) {
            case 'iready': $importerStrategy = $this->getServiceLocator()->get('IreadyImporter'); break;
            case 'dreambox-usage': $importerStrategy = $this->getServiceLocator()->get('DreamboxUsageImporter'); break;
            case 'ttm-student': $importerStrategy = $this->getServiceLocator()->get('ThinkThroughMathStudentImporter'); break;
        }

        $dropbox = $this->getServiceLocator()->get('Dropbox');
        $studentService = $this->getServiceLocator()->get('StudentService');

        $fd = tmpfile();
        //$metadata = $dropbox->getFile($path, $fd);

        $importer->setDropbox($dropbox);
        $importer->setImporter($importerStrategy);
        $importer->setStudentService($studentService);

        $importer->import();

        $this->redirect()->toUrl('/import/history/' . $type);

    }

    public function historyAction() {

        $type = $this->params('type');

        switch ($type){
            case 'iready': $data = $this->getServiceLocator()->get('IreadyService')->all(); break;
            case 'dreambox-usage': $data = $this->getServiceLocator()->get('DreamboxUsageService')->all(); break;
            case 'ttm-student': $data = $this->getServiceLocator()->get('ThinkThroughMathStudentService')->all(); break;
        }

        $this->layout()->activeMenu = 'import';

        return new ViewModel([
            'data' => $data,
            'type' => $type,
        ]);

    }

}
