<?php

namespace MSchool\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class PathwayController extends AbstractActionController
{
    public function indexAction()
    {

        $this->layout('layout/pathway');

        $session = $this->getServiceLocator()->get('StudentSessionContainer');

        $pathwayService = $this->getServiceLocator()->get('PathwayService');

        if (!$session->pathwayContainer) {

            // BUILD DAILY PATHWAY
            $container = $pathwayService->getStudentPathwayForDate();

            $session->pathwayContainer = $container;

        } else {
            $container = $session->pathwayContainer;
        }

        $step = $container->getCurrentStep();

        // DETERMINE VIEW BASED ON CONTENT OF CURRENT STEP


        return new ViewModel(array(
            'container' => $container,
            'step' => $step,
        ));
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

}
