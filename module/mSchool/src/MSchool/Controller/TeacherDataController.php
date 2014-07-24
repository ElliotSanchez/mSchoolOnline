<?php

namespace MSchool\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\Mvc\MvcEvent;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;
use Admin\Teacher\Entity as Teacher;
use Zend\Stdlib\DispatchableInterface;
use Zend\Stdlib\RequestInterface as Request;
use Zend\Stdlib\ResponseInterface as Response;

class TeacherDataController extends AbstractActionController implements DispatchableInterface
{

    public function onDispatch(MvcEvent $mvcEvent) {

        $mvcEvent->getViewModel()->setTerminal(true);

        return parent::onDispatch($mvcEvent);
    }

    public function studentPlacementAction()
    {

        $classId = $this->params('class_id');

        $mclass = $this->getServiceLocator()->get('MclassService')->get($classId);

        $ireadyService = $this->getServiceLocator()->get('IreadyService');

        $data = $ireadyService->getStudentPlacement($mclass);

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

    public function timeOnMathAction() {

        $classId = $this->params('class_id');

        $mclass = $this->getServiceLocator()->get('MclassService')->get($classId);

        $teacherStatService = $this->getServiceLocator()->get('TeacherStatService');

        $tom = $teacherStatService->getTimeOnMath($mclass);

        $json = new JsonModel();
        $objs = [];

        foreach ($tom->getData() as $data) {
            $objs[] = $data;
        }
        $json->setVariables($objs);

        return $json;

    }

    public function learningPointsAction() {

        $classId = $this->params('class_id');

        $mclass = $this->getServiceLocator()->get('MclassService')->get($classId);

        $teacherStatService = $this->getServiceLocator()->get('TeacherStatService');

        $lps = $teacherStatService->getLearningPoints($mclass);

        $json = new JsonModel();
        $objs = [];

        foreach ($lps->getData() as $data) {
            $objs[] = $data;
        }
        $json->setVariables($objs);

        return $json;

    }

    public function mapVisualDataAction() {

        $studentId = $this->params('student_id');

        $student = $this->getServiceLocator()->get('StudentService')->get($studentId);

        $mapData = $this->getServiceLocator()->get('SequenceService')->getStudentMapVisualizationData($student);

        $json = new JsonModel();
        $objs = [];

        foreach ($mapData as $data) {
            $objs[] = $data;
        }
        $json->setVariables($objs);

        return $json;

    }

    public function learningPointsGradeAveragesAction() {

        $classId = $this->params('class_id');

        $mclass = $this->getServiceLocator()->get('MclassService')->get($classId);

        $teacherStatService = $this->getServiceLocator()->get('TeacherStatService');

        $lpavgs = $teacherStatService->getLearningPointsGradeAverages($mclass);

        $json = new JsonModel();
        $objs = [];

        foreach ($lpavgs->getData() as $data) {
            $objs[$data['grade_level_id']] = $data;
        }
        $json->setVariables($objs);

        return $json;


    }

}
