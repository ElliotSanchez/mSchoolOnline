<?php

namespace MSchool\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class IndexController extends AbstractActionController
{
    public function indexAction()
    {

        $this->layout('mschool/layout/layout');

        // TODO THIS ISA LITTLE HEAVY
        $student = $adminAuthService = $this->getServiceLocator()->get('StudentAuthService')->getCurrentUser();
        $container = $this->getServiceLocator()->get('SequenceService')->getStudentSequenceContainerFor($student, new \DateTime());

        $hasSteps = ($container->hasAvailableSteps()) ? (true) : (false);

        return new ViewModel(array(
            'hasSteps' => $hasSteps,
        ));
    }
}
