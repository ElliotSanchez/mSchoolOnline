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

        $plan = $planService->get($this->params('id'));

        $form = $this->getServiceLocator()->get('PlanEditForm');

        $form->bind($plan);

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

        return new ViewModel([
            'form' => $form,
        ]);

    }

}
