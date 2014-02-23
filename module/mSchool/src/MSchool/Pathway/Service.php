<?php

namespace MSchool\Pathway;

use Admin\Form\Upload\Students;
use Admin\ModelAbstract\ServiceAbstract as ServiceAbstract;
use Admin\Resource\Service as ResourceService;
use Admin\Student\Service as StudentService;
use Admin\Student\Entity as Student;
use MSchool\Pathway\Entity as Pathway;
use MSchool\Pathway\Table as PathwayTable;

class Service extends ServiceAbstract
{

    protected $resourceService;
    protected $studentService;

    public function __construct(PathwayTable $pathwayTable, ResourceService $resourceService, StudentService $studentService) {
        parent::__construct($pathwayTable);
        $this->resourceService = $resourceService;
        $this->studentService = $studentService;
    }

    public function create($data) {

        $pathway = new Pathway();

        $pathway->create($data);

        $pathway = $this->table->save($pathway);

        return $pathway;

    }

    public function getStudentPathwayFor(Student $student, \DateTime $date) {

        $container = new Container();

        $select = $this->table->getSql()->select();

        $select->where(array('student_id' => $student->id));
        $select->where(array('pathway_date' => $date->format('Y-m-d')));
        $select->where(array('is_active' => 1));
        $select->order('step ASC');

        $results = $this->table->fetchWith($select);

        if (count($results) < 1) {
            $results = $this->getDefaultPathwayFor($student, $date);
        }

        foreach ($results as $pathway) {
            $step = new Step($this->resourceService->get($pathway->resourceId), null);
            $container->addStep($step);
        }

        return $container;

    }

    public function getDefaultPathwayFor(Student $student, \DateTime $date) {
        $select = $this->table->getSql()->select();

        $select->where(array('student_id' => $student->id));
        $select->where(array('pathway_date' => null));
        $select->where(array('is_active' => 1));
        $select->order('step ASC');

        return $this->table->fetchWith($select);
    }

    public function importPathwaysFromFile($filename) {

        $uploadSetId = time();

        $objPHPExcel = \PHPExcel_IOFactory::load($filename);

        $rowIterator = $objPHPExcel->getActiveSheet()->getRowIterator();

        $STUDENT_NAME_COL = 'A';
        $STUDENT_NUM_COL = 'B';
        $PATHWAY_DATE = 'C';
        $ORDER_COL = 'D';
        $RESOURCE_COL = 'E';
        $TIMER_COL = 'F';

        foreach ($rowIterator as $row) {

            $cellIterator = $row->getCellIterator();
            $cellIterator->setIterateOnlyExistingCells(false); // Loop all cells, even if it is not set

            if(1 == $row->getRowIndex ()) continue; //skip first row

            foreach ($cellIterator as $cell) {

                switch ($cell->getColumn()) {
                    case $STUDENT_NAME_COL: $studentName = $cell->getValue();
                        break;
                    case $STUDENT_NUM_COL:  $studentNumber = $cell->getValue();
                        break;
                    case $PATHWAY_DATE:     $pathwayDateString = $cell->getValue();
                        break;
                    case $ORDER_COL:        $pathwayOrder = $cell->getValue();
                        break;
                    case $RESOURCE_COL:     $resourceIdentifier = $cell->getValue();
                        break;
                    case $TIMER_COL:        $pathwayTimer = $cell->getValue();
                        break;
                    default:                break;
                }
            }

            // FIND STUDENT
            $student = $this->studentService->getWithStudentNumber($studentNumber);

            // FIND RESOURCE
            $resource = $this->resourceService->getWithShortCode($resourceIdentifier); // THIS MAY BE OTHER THINGS LATER

            echo $student->firstName . ' ' . $student->lastName . ' ' . $resource->url . '<br>';

            // DETERMINE DATE OR DEFAULT
            $pathwayDate = null; // ASSUME DEFAULT

            if (strlen($pathwayDateString)) {
                try {
                    $pathwayDate = new \DateTime($pathwayDateString);
                } catch(Exception $e) {
                    // PARSING FAILED SO LEAVE IT AS DEFAULT
                }
            }

            $data = array(
                'student_id' => $student->id,
                'resource_id' => $resource->id,
                'pathway_date' => ($pathwayDate instanceof \DateTime) ? ($pathwayDate->format('Y-m-d')) : ($pathwayDate),
                'order' => $pathwayOrder,
                'timer' => $pathwayTimer,
                'is_active' => 1,
                'upload_set_id' => $uploadSetId,
            );

            // INACTIVATE OLD PATHWAYS FOR DATES IN FILE
            // TODO DO THIS IN AGGREGATE INSTEAD OF PER ROW
            $this->inactivatePathwaysFor($student, $pathwayDate, $uploadSetId);

            // CREATE PATHWAY
            $this->create($data);

        }

    }

    public function inactivatePathwaysFor(Student $student, \DateTime $dateTime = null, $uploadSetId) {

        $dateTimeString = ($dateTime) ? ($dateTime->format('Y-m-d')) : (null);

        $where = new \Zend\Db\Sql\Where();

        $where->equalTo('student_id', $student->id)
            ->equalTo('is_active', 1)
            ->lessThan('upload_set_id', $uploadSetId);

        if ($dateTimeString == null)
            $where->isNull('pathway_date');
        else
            $where->equalTo('pathway_date', $dateTimeString);

        return $this->table->update(array('is_active' => 0), $where);

    }

}