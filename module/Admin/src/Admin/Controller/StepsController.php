<?php

namespace Admin\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class StepsController extends AbstractActionController
{
    public function indexAction()
    {
        $this->layout()->pageTitle = 'Steps';
        $this->layout()->activeMenu = 'structures';

        $steps = iterator_to_array($this->getServiceLocator()->get('StepService')->all());

        foreach ($steps as &$step) {
            $step->resource = $this->getServiceLocator()->get('ResourceService')->get($step->resourceId);
        }

        return new ViewModel(array(
            'steps' => $steps,
        ));
    }

    public function addAction()
    {

        $form = $this->getServiceLocator()->get('StepAddForm');

        $request = $this->getRequest();

        if ($request->isPost()) {

            $form->setData($request->getPost());

            if ($form->isValid()) {
                $data = $form->getData();

                $step = $this->getServiceLocator()->get('StepService')->create($data);

                $this->flashMessenger()->addSuccessMessage('Added Step');
                $this->redirect()->toRoute('admin/step_edit', array('id' => $step->id));
            }

        }

        $this->layout()->pageTitle = 'Steps > Add';
        $this->layout()->activeMenu = 'structures';

        return new ViewModel([
            'form' => $form,
        ]);

    }

    public function editAction() {

        $stepService = $this->getServiceLocator()->get('StepService');

        $step = $stepService->get($this->params('id'));

        $form = $this->getServiceLocator()->get('StepEditForm');

        $form->bind($step);

        $request = $this->getRequest();

        if ($request->isPost()) {

            $form->setData($request->getPost());

            if ($form->isValid()) {

                $step = $form->getData();

                $stepService->save($step);

                $this->flashMessenger()->addSuccessMessage('Updated Step');
                $this->redirect()->toRoute('admin/step_edit', array('id' => $step->id));
            } else {
                print_r($form->getMessages());
            }

        }

        $this->layout()->pageTitle = 'Steps > Edit';
        $this->layout()->activeMenu = 'structures';

        return new ViewModel([
            'form' => $form,
        ]);

    }

}
