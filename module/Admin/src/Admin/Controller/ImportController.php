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
            case 'digitwhiz-mastery': $importerStrategy = $this->getServiceLocator()->get('DigitWhizMasteryImporter'); break;
            case 'digitwhiz-time': $importerStrategy = $this->getServiceLocator()->get('DigitWhizTimeImporter'); break;
            case 'dreambox-usage': $importerStrategy = $this->getServiceLocator()->get('DreamboxUsageImporter'); break;
            case 'dreambox-standards': $importerStrategy = $this->getServiceLocator()->get('DreamboxStandardsImporter'); break;
            case 'stmath-progress': $importerStrategy = $this->getServiceLocator()->get('STMathProgressImporter'); break;
            case 'stmath-student': $importerStrategy = $this->getServiceLocator()->get('STMathStudentImporter'); break;
            case 'stmath-usage': $importerStrategy = $this->getServiceLocator()->get('STMathUsageImporter'); break;
            case 'ttm-student': $importerStrategy = $this->getServiceLocator()->get('ThinkThroughMathStudentImporter'); break;
            case 'ttm-overview': $importerStrategy = $this->getServiceLocator()->get('ThinkThroughMathOverviewImporter'); break;
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
            case 'iready':              $data = $this->getServiceLocator()->get('IreadyService')->all(); break;
            case 'digitwhiz-mastery':   $data = $this->getServiceLocator()->get('DigitWhizMasteryService')->all(); break;
            case 'digitwhiz-time':      $data = $this->getServiceLocator()->get('DigitWhizTimeService')->all(); break;
            case 'dreambox-usage':      $data = $this->getServiceLocator()->get('DreamboxUsageService')->all(); break;
            case 'dreambox-standards':  $data = $this->getServiceLocator()->get('DreamboxStandardsService')->all(); break;
            case 'stmath-progress':     $data = $this->getServiceLocator()->get('STMathProgressService')->all(); break;
            case 'stmath-student':      $data = $this->getServiceLocator()->get('STMathStudentService')->all(); break;
            case 'stmath-usage':        $data = $this->getServiceLocator()->get('STMathUsageService')->all(); break;
            case 'ttm-student':         $data = $this->getServiceLocator()->get('ThinkThroughMathStudentService')->all(); break;
            case 'ttm-overview':        $data = $this->getServiceLocator()->get('ThinkThroughMathOverviewService')->all(); break;
        }

        $this->layout()->activeMenu = 'import';

        return new ViewModel([
            'data' => $data,
            'type' => $type,
        ]);

    }

}
