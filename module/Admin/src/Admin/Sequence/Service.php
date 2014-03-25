<?php

namespace Admin\Sequence;

//use Admin\ModelAbstract\ServiceAbstract as ServiceAbstract;
use Admin\Pathway\Service as PathwayService;
use Admin\Plan\Service as PlanService;
use Admin\Step\Service as StepService;
use Admin\Resource\Service as ResourceService;
use Admin\PathwayPlan\Service as PathwayPlanService;
use Admin\PlanStep\Service as PlanStepService;
use Admin\StudentStep\Service as StudentStepService;
use Admin\Student\Service as StudentService;
use Admin\Student\Entity as Student;
use MSchool\Pathway\Entity as Pathway;
use MSchool\Pathway\Table as PathwayTable;

class Service
{

    protected $pathwayService;
    protected $planService;
    protected $stepService;
    protected $pathwayPlanService;
    protected $planStepService;
    protected $studentStepService;
    protected $resourceService;
    protected $studentService;

    protected $pathwayCache;
    protected $planCache;

    public function __construct(PathwayService $pathwayService, PlanService $planService, StepService $stepService, PathwayPlanService $pathwayPlanService, PlanStepService $planStepService, StudentStepService $studentStepService, ResourceService $resourceService, StudentService $studentService) {
        $this->pathwayService = $pathwayService;
        $this->planService = $planService;
        $this->stepService = $stepService;
        $this->pathwayPlanService = $pathwayPlanService;
        $this->planStepService = $planStepService;
        $this->studentStepService = $studentStepService;
        $this->resourceService = $resourceService;
        $this->studentService = $studentService;
    }

    public function importSequencesFromFile($filename) {

        $uploadSetId = time();

        $objPHPExcel = \PHPExcel_IOFactory::load($filename);

        $rowIterator = $objPHPExcel->getActiveSheet()->getRowIterator();

        // SEQUENCE DATA
        $this->pathwayCache = $this->pathwayService->getMappedPathways();
        $this->planCache = $this->planService->getMappedPlans();

        // FILE DEFINITION
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
                    case $SEQUENCE_1:       $sequence1Code = $cell->getValue();
                        break;
                    case $SEQUENCE_2:       $sequence2Code = $cell->getValue();
                        break;
                    case $SEQUENCE_3:       $sequence3Code = $cell->getValue();
                        break;
                    case $SEQUENCE_4:       $sequence4Code = $cell->getValue();
                        break;
                    case $SEQUENCE_5:       $sequence5Code = $cell->getValue();
                        break;

                    default:                break;
                }
            }

            // FIND STUDENT
            $student = $this->studentService->getWithStudentNumber($studentNumber);

//            if ($sequenceDefaultCode) {
//                if (isset($pathways[$sequenceDefaultCode])) {
//                    // TODO SET DEFAULT PATHWAY SEQUENCE
//                } else if (isset($plans[$sequenceDefaultCode])) {
//                    // TODO SET DEFAULT PLAN SEQUENCE
//                }
//            }


            $this->assignSteps($sequence1Code, $student);
            }
            // FIND RESOURCE
            //$resource = $this->resourceService->getWithShortCode($resourceIdentifier); // THIS MAY BE OTHER THINGS LATER

            //echo $student->firstName . ' ' . $student->lastName . ' ' . $resource->url . '<br>';

            // DETERMINE DATE OR DEFAULT
//            $pathwayDate = null; // ASSUME DEFAULT



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

        die('>'.__LINE__);

    }

    public function assignSteps($shortCode, $student) {

        echo $student->id . ' => ' . $shortCode . '<br>';

        if (isset($this->pathwayCache[$shortCode])) {

            $pathway = $this->pathwayCache[$shortCode];
            $pathwayPlans = $this->pathwayPlanService->getPathwayPlan($pathway);

            foreach ($pathwayPlans as $pathwayPlan) {

                $planSteps = $this->planStepService->getPlanSteps($pathwayPlan->plan);

                foreach ($planSteps as $planStep) {

                    $step = $planStep->step;

                    // CREATE STEPS
                    $this->studentStepService->create(array(
                        'step_order' => 1,
                        'student_id' => $student->id,
                        'pathway_id' => $pathway->id,
                        'plan_id' => $pathwayPlan->plan->id,
                        'step_id' => $step->id,
                    ));

                }

            }

        } else if (isset($this->planCache[$shortCode])) {

            $plan = $this->planCache[$shortCode];

            $planSteps = $this->planStepService->getPlanSteps($plan);

            foreach ($planSteps as $planStep) {

                $step = $planStep->step;

                // CREATE STEPS
                $this->studentStepService->create(array(
                    'step_order' => 1,
                    'student_id' => $student->id,
                    'pathway_id' => null,
                    'plan_id' => $plan->id,
                    'step_id' => $step->id,
                ));

            }


        }

    }

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