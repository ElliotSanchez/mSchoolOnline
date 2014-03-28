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

        foreach ($results as $pathway) {
            $step = new Step($this->resourceService->get($pathway->resourceId), $pathway->timer);
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

        $STUDENT_FNAME_COL = 'A';
        $STUDENT_LNAME_COL = 'B';
        $STUDENT_NUM_COL = 'C';
        $SEQUENCE_DEFAULT = 'D';
        $SEQUENCE_1 = 'E';
        $SEQUENCE_2 = 'F';
        $SEQUENCE_3 = 'G';
        $SEQUENCE_4 = 'H';
        $SEQUENCE_5 = 'I';

        foreach ($rowIterator as $row) {

            $cellIterator = $row->getCellIterator();
            $cellIterator->setIterateOnlyExistingCells(false); // Loop all cells, even if it is not set

            if(1 == $row->getRowIndex ()) continue; //skip first row

            foreach ($cellIterator as $cell) {

                switch ($cell->getColumn()) {
                    case $STUDENT_FNAME_COL: $studentFName = $cell->getValue();
                        break;
                    case $STUDENT_LNAME_COL: $studentLName = $cell->getValue();
                        break;
                    case $STUDENT_NUM_COL:  $studentNumber = $cell->getValue();
                        break;
                    case $SEQUENCE_DEFAULT: $sequenceDefaultCode = $cell->getValue();
                        break;
                    case $SEQUENCE_1:       $sequence1 = $cell->getValue();
                        break;
                    case $SEQUENCE_2:       $sequence2 = $cell->getValue();
                        break;
                    case $SEQUENCE_3:       $sequence3 = $cell->getValue();
                        break;
                    case $SEQUENCE_4:       $sequence4 = $cell->getValue();
                        break;
                    case $SEQUENCE_5:       $sequence5 = $cell->getValue();
                        break;

                    default:                break;
                }
            }

            // FIND STUDENT
            $student = $this->studentService->getWithStudentNumber($studentNumber);

            // FIND RESOURCE
            //$resource = $this->resourceService->getWithShortCode($resourceIdentifier); // THIS MAY BE OTHER THINGS LATER

            //echo $student->firstName . ' ' . $student->lastName . ' ' . $resource->url . '<br>';

            // DETERMINE DATE OR DEFAULT
            $pathwayDate = null; // ASSUME DEFAULT



//            if (strlen($pathwayDateString)) {
//                try {
//                    $pathwayDate = new \DateTime($pathwayDateString);
//                } catch(Exception $e) {
//                    // PARSING FAILED SO LEAVE IT AS DEFAULT
//                }
//            }

//            $data = array(
//                'student_id' => $student->id,
//                'resource_id' => $resource->id,
//                'pathway_date' => ($pathwayDate instanceof \DateTime) ? ($pathwayDate->format('Y-m-d')) : ($pathwayDate),
//                'step' => $stepOrder,
//                'timer' => $pathwayTimer,
//                'is_active' => 1,
//                'upload_set_id' => $uploadSetId,
//            );

            // INACTIVATE OLD PATHWAYS FOR DATES IN FILE
            // TODO DO THIS IN AGGREGATE INSTEAD OF PER ROW
//            $this->inactivatePathwaysFor($student, $pathwayDate, $uploadSetId);

            // CREATE PATHWAY
//            $this->create($data);

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