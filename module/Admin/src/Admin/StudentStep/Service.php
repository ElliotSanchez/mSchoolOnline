<?php

namespace Admin\StudentStep;

use Admin\ModelAbstract\ServiceAbstract as ServiceAbstract;
use Admin\Resource\Service as ResourceService;
use Admin\Student\Service as StudentService;
use Admin\Student\Entity as Student;
use Admin\StudentStep\Entity as Step;
use MSchool\Pathway\Table as StepTable;

class Service extends ServiceAbstract
{

    protected $resourceService;
    protected $studentService;

//    public function __construct(StepTable $stepTable, ResourceService $resourceService, StudentService $studentService) {
//        parent::__construct($stepTable);
//        $this->resourceService = $resourceService;
//        $this->studentService = $studentService;
//    }

    public function create($data) {

        $step = new Step();

        $step->create($data);

        $step = $this->table->save($step);

        return $step;

    }

//    public function getStudentPathwayFor(Student $student, \DateTime $date) {
//
//        $container = new Container();
//
//        $select = $this->table->getSql()->select();
//
//        $select->where(array('student_id' => $student->id));
//        $select->where(array('pathway_date' => $date->format('Y-m-d')));
//        $select->where(array('is_active' => 1));
//        $select->order('step ASC');
//
//        $results = $this->table->fetchWith($select);
//
//        if (count($results) < 1) {
//            $results = $this->getDefaultPathwayFor($student, $date);
//        }
//
//        foreach ($results as $pathway) {
//            $step = new Step($this->resourceService->get($pathway->resourceId), $pathway->timer);
//            $container->addStep($step);
//        }
//
//        return $container;
//
//    }
//
//    public function getDefaultPathwayFor(Student $student, \DateTime $date) {
//        $select = $this->table->getSql()->select();
//
//        $select->where(array('student_id' => $student->id));
//        $select->where(array('pathway_date' => null));
//        $select->where(array('is_active' => 1));
//        $select->order('step ASC');
//
//        return $this->table->fetchWith($select);
//    }
//
//    public function importPathwaysFromFile($filename) {
//
//        $uploadSetId = time();
//
//        $objPHPExcel = \PHPExcel_IOFactory::load($filename);
//
//        $rowIterator = $objPHPExcel->getActiveSheet()->getRowIterator();
//
//        $STUDENT_NAME_COL = 'A';
//        $STUDENT_NUM_COL = 'B';
//        $PATHWAY_DATE = 'C';
//        $STEP_COL = 'D';
//        $RESOURCE_COL = 'E';
//        $TIMER_COL = 'F';
//
//        foreach ($rowIterator as $row) {
//
//            $cellIterator = $row->getCellIterator();
//            $cellIterator->setIterateOnlyExistingCells(false); // Loop all cells, even if it is not set
//
//            if(1 == $row->getRowIndex ()) continue; //skip first row
//
//            foreach ($cellIterator as $cell) {
//
//                switch ($cell->getColumn()) {
//                    case $STUDENT_NAME_COL: $studentName = $cell->getValue();
//                        break;
//                    case $STUDENT_NUM_COL:  $studentNumber = $cell->getValue();
//                        break;
//                    case $PATHWAY_DATE:     $pathwayDateString = $cell->getValue();
//                        break;
//                    case $STEP_COL:         $stepOrder = $cell->getValue();
//                        break;
//                    case $RESOURCE_COL:     $resourceIdentifier = $cell->getValue();
//                        break;
//                    case $TIMER_COL:        $pathwayTimer = $cell->getValue();
//                        break;
//                    default:                break;
//                }
//            }
//
//            // FIND STUDENT
//            $student = $this->studentService->getWithStudentNumber($studentNumber);
//
//            // FIND RESOURCE
//            $resource = $this->resourceService->getWithShortCode($resourceIdentifier); // THIS MAY BE OTHER THINGS LATER
//
//            echo $student->firstName . ' ' . $student->lastName . ' ' . $resource->url . '<br>';
//
//            // DETERMINE DATE OR DEFAULT
//            $pathwayDate = null; // ASSUME DEFAULT
//
//            if (strlen($pathwayDateString)) {
//                try {
//                    $pathwayDate = new \DateTime($pathwayDateString);
//                } catch(Exception $e) {
//                    // PARSING FAILED SO LEAVE IT AS DEFAULT
//                }
//            }
//
//            $data = array(
//                'student_id' => $student->id,
//                'resource_id' => $resource->id,
//                'pathway_date' => ($pathwayDate instanceof \DateTime) ? ($pathwayDate->format('Y-m-d')) : ($pathwayDate),
//                'step' => $stepOrder,
//                'timer' => $pathwayTimer,
//                'is_active' => 1,
//                'upload_set_id' => $uploadSetId,
//            );
//
//            // INACTIVATE OLD PATHWAYS FOR DATES IN FILE
//            // TODO DO THIS IN AGGREGATE INSTEAD OF PER ROW
//            $this->inactivatePathwaysFor($student, $pathwayDate, $uploadSetId);
//
//            // CREATE PATHWAY
//            $this->create($data);
//
//        }
//
//    }
//
//    public function inactivatePathwaysFor(Student $student, \DateTime $dateTime = null, $uploadSetId) {
//
//        $dateTimeString = ($dateTime) ? ($dateTime->format('Y-m-d')) : (null);
//
//        $where = new \Zend\Db\Sql\Where();
//
//        $where->equalTo('student_id', $student->id)
//            ->equalTo('is_active', 1)
//            ->lessThan('upload_set_id', $uploadSetId);
//
//        if ($dateTimeString == null)
//            $where->isNull('pathway_date');
//        else
//            $where->equalTo('pathway_date', $dateTimeString);
//
//        return $this->table->update(array('is_active' => 0), $where);
//
//    }

}