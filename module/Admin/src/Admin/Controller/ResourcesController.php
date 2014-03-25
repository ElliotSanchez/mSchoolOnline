<?php

namespace Admin\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class ResourcesController extends AbstractActionController
{
    public function indexAction()
    {

        $this->layout()->pageTitle = 'Resources';

        return new ViewModel(array(
            'resources' => $this->getServiceLocator()->get('ResourceService')->all(),
        ));
    }

    public function addAction()
    {

        $form = $this->getServiceLocator()->get('ResourceAddForm');

        $request = $this->getRequest();

        if ($request->isPost()) {

            $form->setData($request->getPost());

            if ($form->isValid()) {
                $data = $form->getData();

                $resource = $this->getServiceLocator()->get('ResourceService')->create($data);

                $this->flashMessenger()->addSuccessMessage('Added Resource');
                $this->redirect()->toRoute('admin/resource_edit', array('id' => $resource->id));
            }

        }

        $this->layout()->pageTitle = 'Resources > Add';

        return new ViewModel([
            'form' => $form,
        ]);

    }

    public function editAction() {

        $this->layout()->pageTitle = 'Resources > Edit';

        $resourceService = $this->getServiceLocator()->get('ResourceService');

        $resource = $resourceService->get($this->params('id'));

        $form = $this->getServiceLocator()->get('ResourceEditForm');

        $form->bind($resource);

        $request = $this->getRequest();

        if ($request->isPost()) {

            $form->setData($request->getPost());

            if ($form->isValid()) {

                $resource = $form->getData();

                $resourceService->save($resource);

                $this->flashMessenger()->addSuccessMessage('Updated Resource');
                $this->redirect()->toRoute('admin/resource_edit', array('id' => $resource->id));
            }

        }

        return new ViewModel([
            'form' => $form,
        ]);

    }

}
