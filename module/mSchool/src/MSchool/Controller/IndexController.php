<?php

namespace MSchool\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class IndexController extends AbstractActionController
{
    public function indexAction()
    {

        $this->layout('mschool/layout/layout');

        // TODO THIS IS A LITTLE HEAVY
        $student = $adminAuthService = $this->getServiceLocator()->get('StudentAuthService')->getCurrentUser();
        $sequenceService = $this->getServiceLocator()->get('SequenceService');

        $session = $this->getServiceLocator()->get('StudentSessionContainer');

        if (!$session->pathwayContainer) {
            $container = $sequenceService->getStudentSequenceContainerFor($student, new \DateTime());
            $session->pathwayContainer = $container;
        } else {
            $container = $session->pathwayContainer;
        }

        $hasSteps = ($container && $container->hasAvailableSteps()) ? (true) : (false);


        $extraCreditOptions = null;

        if (!$hasSteps || ($container && $container->readyForExtraCredit())) {
            try {
                $extraCreditOptions = $this->getServiceLocator()->get('SequenceService')->getExtraCreditResourceOptions($student);
            } catch (\Exception $e) {
                //print_r($e);die();
            }
        }

        return new ViewModel(array(
            'hasSteps' => $hasSteps,
            'extraCreditOptions' => $extraCreditOptions,
        ));

    }
}
