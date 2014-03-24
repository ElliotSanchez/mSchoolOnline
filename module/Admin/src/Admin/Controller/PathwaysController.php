<?php

namespace Admin\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class PathwaysController extends AbstractActionController
{
    public function indexAction()
    {
        return new ViewModel(array(
            'pathways' => $this->getServiceLocator()->get('PathwayService')->all(),
        ));
    }

    public function addAction()
    {

        $form = $this->getServiceLocator()->get('PathwayAddForm');

        $request = $this->getRequest();

        if ($request->isPost()) {

            $form->setData($request->getPost());

            if ($form->isValid()) {
                $data = $form->getData();

                $pathway = $this->getServiceLocator()->get('PathwayService')->create($data);

                $this->flashMessenger()->addSuccessMessage('Added Pathway');
                $this->redirect()->toRoute('admin/pathway_edit', array('id' => $pathway->id));
            }

        }

        return new ViewModel([
            'form' => $form,
        ]);

    }

    public function editAction() {

        $pathwayService = $this->getServiceLocator()->get('PathwayService');
        $pathwayPlanService = $this->getServiceLocator()->get('PathwayPlanService');

        $pathway = $pathwayService->get($this->params('id'));

        // FORMS
        $form = $this->getServiceLocator()->get('PathwayEditForm');
        $form->bind($pathway);

        $addPlanForm = $this->getServiceLocator()->get('PathwayPlanAddForm');
        $removePlanForm = $this->getServiceLocator()->get('PathwayPlanRemoveForm');

        $request = $this->getRequest();

        if ($request->isPost()) {

            $form->setData($request->getPost());

            if ($form->isValid()) {

                $pathway = $form->getData();

                $pathwayService->save($pathway);

                $this->flashMessenger()->addSuccessMessage('Updated Pathway');
                $this->redirect()->toRoute('admin/pathway_edit', array('id' => $pathway->id));
            } else {
                print_r($form->getMessages());
            }

        }

        $pathwayPlans = $pathwayPlanService->getPathwayPlan($pathway);

        return new ViewModel([
            'pathway' => $pathway,
            'pathwayPlans' => $pathwayPlans,
            'form' => $form,
            'addPlanForm' => $addPlanForm,
            'removePlanForm' => $removePlanForm,
        ]);

    }

    public function addPlanAction() {

        $pathwayService = $this->getServiceLocator()->get('PathwayService');
        $pathwayPlanService = $this->getServiceLocator()->get('PathwayPlanService');

        $pathway = $pathwayService->get($this->params('id'));

        $addPlanForm = $this->getServiceLocator()->get('PathwayPlanAddForm');

        // FORM PROCESSING
        $request = $this->getRequest();

        $failed = false;

        if ($request->isPost()) {

            $addPlanForm->setData($request->getPost());

            if ($addPlanForm->isValid()) {

                $planId = $addPlanForm->getInputFilter()->getRawValue('plan_id');

                $pathwayPlanService->create(array(
                    'pathway_id' => $pathway->id,
                    'plan_id' => $planId,
                ));

                //$pathwayService->save($pathway);

                $this->flashMessenger()->addSuccessMessage('Added Plan to Pathway');
                $this->redirect()->toRoute('admin/pathway_edit', array('id' => $pathway->id));
                return;
            } else {
                $failed = true;
            }

        } else {
            $failed = true;
        }

        if ($failed) {
            $this->flashMessenger()->addErrorMessage('Unable to add Plan to Pathway');
        }

        $this->redirect()->toRoute('admin/pathway_edit', array('id' => $pathway->id));

        return;

    }

    public function removePlanAction() {

        $pathwayService = $this->getServiceLocator()->get('PathwayService');
        $pathwayPlanService = $this->getServiceLocator()->get('PathwayPlanService');

        $pathway= $pathwayService->get($this->params('id'));

        $removePlanForm = $this->getServiceLocator()->get('PathwayPlanRemoveForm');

        // FORM PROCESSING
        $request = $this->getRequest();

        $failed = false;

        if ($request->isPost()) {

            $removePlanForm->setData($request->getPost());

            if ($removePlanForm->isValid()) {

                $pathwayPlanId = $removePlanForm->getInputFilter()->getRawValue('pathwayplan_id');

                $pathwayPlanService->removePathwayPlan($pathwayPlanService->get($pathwayPlanId));

                $this->flashMessenger()->addSuccessMessage('Removed Plan from Pathway');
                $this->redirect()->toRoute('admin/pathway_edit', array('id' => $pathway->id));
                return;
            } else {
                $failed = true;
            }

        } else {
            $failed = true;
        }

        if ($failed) {
            $this->flashMessenger()->addErrorMessage('Unable to remove Plan from Pathway');
        }

        $this->redirect()->toRoute('admin/pathway_edit', array('id' => $pathway->id));

        return;

    }

}
