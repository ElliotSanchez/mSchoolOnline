<?php

namespace MSchool\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;
use Admin\Teacher\Entity as Teacher;
use Zend\Stdlib\DispatchableInterface;
use Zend\Stdlib\RequestInterface as Request;
use Zend\Stdlib\ResponseInterface as Response;

class TeacherDataController extends AbstractActionController implements DispatchableInterface
{

    public function studentPlacementAction()
    {

        $ireadyService = $this->getServiceLocator()->get('IreadyService');

        $data = $ireadyService->getStudentPlacement();

        $json = new JsonModel();
        $objs = [];

        foreach ($data as $currData) {
            $obj = new \stdClass();
            $obj->student = $currData->firstName . ' ' . $currData->lastName;
            $obj->score = $currData->diagnosticOverallScaleScore;
            $objs[] = $obj;
        }

        $json->setVariables($objs);

        return $json;

    }

}
