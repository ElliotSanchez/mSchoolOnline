<?php

namespace MSchool\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;

class PathwayController extends AbstractActionController
{
    public function indexAction()
    {

        $this->layout('layout/pathway');

        $session = $this->getServiceLocator()->get('StudentSessionContainer');

        //$pathwayService = $this->getServiceLocator()->get('PathwayService');

        $sequenceService = $this->getServiceLocator()->get('SequenceService');

        $student = $adminAuthService = $this->getServiceLocator()->get('StudentAuthService')->getCurrentUser();

        if (!$session->pathwayContainer) {

            $container = $sequenceService->getStudentSequenceContainerFor($student, new \DateTime());

            $session->pathwayContainer = $container;

        } else {
            $container = $session->pathwayContainer;
        }

        $step = $container->getCurrentStep();

        // DETERMINE VIEW BASED ON CONTENT OF CURRENT STEP

        $viewModel = new ViewModel(array(
            'container' => $container,
            'step' => $step,
        ));

        if ($step->isTimed() && !$container->isAtLastStep())
            $this->getServiceLocator()->get('ViewHelperManager')->get('InlineScript')->appendFile('/assets/mschool/js/pathway-timer.js');

        return $viewModel;
    }

    public function nextAction() {

        // THIS JUST MOVE THE CURRENT CONTAINER A STEP FORWARD AND REDIRECTS TO THE INDEX
        $session = $this->getServiceLocator()->get('StudentSessionContainer');

        $session->pathwayContainer->next();

        return $this->redirect()->toRoute('mschool/pathway');

    }

    public function previousAction() {

        // THIS JUST MOVES THE CURRENT CONTAINER A STEP BACKWARD AND REDIRECTS TO THE INDEX
        $session = $this->getServiceLocator()->get('StudentSessionContainer');

        $session->pathwayContainer->previous();

        return $this->redirect()->toRoute('mschool/pathway');

    }

    public function resetAction() {

        // THIS IS JUST FOR TESTING
        $session = $this->getServiceLocator()->get('StudentSessionContainer');

        $session->pathwayContainer= null;

        return $this->redirect()->toRoute('mschool/pathway');

    }

    public function timerAction() {

        $session = $this->getServiceLocator()->get('StudentSessionContainer');

        if (!$session->pathwayContainer) {
            return; // WHAT?
        }

        $container = $session->pathwayContainer;

        $step = $container->getCurrentStep();

        if ($step->isTimed())
            $time = $step->time;
        else
            $time = null;

        $json = new JsonModel();
        $json->setVariable('timer', $time);

        return $json;

    }

}
