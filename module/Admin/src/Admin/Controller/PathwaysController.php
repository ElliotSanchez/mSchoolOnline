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

        $pathway = $pathwayService->get($this->params('id'));

        $form = $this->getServiceLocator()->get('PathwayEditForm');

        $form->bind($pathway);

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

        return new ViewModel([
            'form' => $form,
        ]);

    }

}
