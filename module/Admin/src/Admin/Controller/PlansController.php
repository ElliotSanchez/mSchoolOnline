<?php

namespace Admin\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class PlansController extends AbstractActionController
{
    public function indexAction()
    {
        return new ViewModel(array(
            'plans' => $this->getServiceLocator()->get('PlanService')->all(),
        ));
    }

    public function addAction()
    {

        $form = $this->getServiceLocator()->get('PlanAddForm');

        $request = $this->getRequest();

        if ($request->isPost()) {

            $form->setData($request->getPost());

            if ($form->isValid()) {
                $data = $form->getData();

                $plan = $this->getServiceLocator()->get('PlanService')->create($data);

                $this->flashMessenger()->addSuccessMessage('Added Plan');
                $this->redirect()->toRoute('admin/plan_edit', array('id' => $plan->id));
            }

        }

        return new ViewModel([
            'form' => $form,
        ]);

    }

    public function editAction() {

        $planService = $this->getServiceLocator()->get('PlanService');
        $planStepService = $this->getServiceLocator()->get('PlanStepService');

        $plan = $planService->get($this->params('id'));

        $form = $this->getServiceLocator()->get('PlanEditForm');
        $form->bind($plan);

        $addStepForm = $this->getServiceLocator()->get('PlanStepAddForm');
        $removeStepForm = $this->getServiceLocator()->get('PlanStepRemoveForm');

        $request = $this->getRequest();

        if ($request->isPost()) {

            $form->setData($request->getPost());

            if ($form->isValid()) {

                $plan = $form->getData();

                $planService->save($plan);

                $this->flashMessenger()->addSuccessMessage('Updated Plan');
                $this->redirect()->toRoute('admin/plan_edit', array('id' => $plan->id));
            } else {
                print_r($form->getMessages());
            }

        }

        $planSteps = $planStepService->getPlanSteps($plan);

        return new ViewModel([
            'plan' => $plan,
            'planSteps' => $planSteps,
            'form' => $form,
            'addStepForm' => $addStepForm,
            'removeStepForm' => $removeStepForm,
        ]);

    }

    public function addStepAction() {

        $planService = $this->getServiceLocator()->get('PlanService');
        $planStepService = $this->getServiceLocator()->get('PlanStepService');

        $plan = $planService->get($this->params('id'));

        $addStepForm = $this->getServiceLocator()->get('PlanStepAddForm');

        // FORM PROCESSING
        $request = $this->getRequest();

        $failed = false;

        if ($request->isPost()) {

            $addStepForm->setData($request->getPost());

            if ($addStepForm->isValid()) {

                $stepId = $addStepForm->getInputFilter()->getRawValue('step_id');

                $planStepService->create(array(
                    'plan_id' => $plan->id,
                    'step_id' => $stepId,
                ));

                //$planService->save($plan);

                $this->flashMessenger()->addSuccessMessage('Added Step to Plan');
                $this->redirect()->toRoute('admin/plan_edit', array('id' => $plan->id));
                return;
            } else {
                $failed = true;
            }

        } else {
            $failed = true;
        }

        if ($failed) {
            $this->flashMessenger()->addErrorMessage('Unable to add Step to Plan');
        }

        $this->redirect()->toRoute('admin/plan_edit', array('id' => $plan->id));

        return;

    }

    public function removeStepAction() {

        $planService = $this->getServiceLocator()->get('PlanService');
        $planStepService = $this->getServiceLocator()->get('PlanStepService');

        $plan= $planService->get($this->params('id'));

        $removeStepForm = $this->getServiceLocator()->get('PlanStepRemoveForm');

        // FORM PROCESSING
        $request = $this->getRequest();

        $failed = false;

        if ($request->isPost()) {

            $removeStepForm->setData($request->getPost());

            if ($removeStepForm->isValid()) {

                $planStepId = $removeStepForm->getInputFilter()->getRawValue('planstep_id');

                $planStepService->removePlanStep($planStepService->get($planStepId));

                $this->flashMessenger()->addSuccessMessage('Removed Step from Plan');
                $this->redirect()->toRoute('admin/plan_edit', array('id' => $plan->id));
                return;
            } else {
                $failed = true;
            }

        } else {
            $failed = true;
        }

        if ($failed) {
            $this->flashMessenger()->addErrorMessage('Unable to remove Step from Plan');
        }

        $this->redirect()->toRoute('admin/plan_edit', array('id' => $plan->id));

        return;

    }


}
