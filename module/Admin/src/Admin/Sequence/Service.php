<?php

namespace Admin\Sequence;

use Admin\ModelAbstract\ServiceAbstract as ServiceAbstract;
use Admin\Sequence\Entity as Sequence;
use Admin\Sequence\Table as SequenceTable;
use Admin\Pathway\Service as PathwayService;
use Admin\Plan\Service as PlanService;
use Admin\Step\Service as StepService;
use Admin\Resource\Service as ResourceService;
use Admin\PathwayPlan\Service as PathwayPlanService;
use Admin\PlanStep\Service as PlanStepService;
use Admin\StudentStep\Service as StudentStepService;
use Admin\Student\Service as StudentService;
use Admin\Student\Entity as Student;
use Admin\Progression\Entity as Progression;
use Admin\Progression\Service as ProgressionService;
use MSchool\Pathway\Container as Container;
use Zend\Db\Sql\Sql;
use Zend\Db\Adapter\Adapter as Adapter;

class Service extends ServiceAbstract implements \Zend\Db\Adapter\AdapterAwareInterface
{

    protected $pathwayService;
    protected $planService;
    protected $stepService;
    protected $pathwayPlanService;
    protected $planStepService;
    protected $studentStepService;
    protected $resourceService;
    protected $studentService;
    protected $progressionService;

    protected $pathwayCache;
    protected $planCache;

    protected $adapter;

    public function __construct(SequenceTable $sequenceTable, PathwayService $pathwayService, PlanService $planService, StepService $stepService, PathwayPlanService $pathwayPlanService, PlanStepService $planStepService, StudentStepService $studentStepService, ResourceService $resourceService, StudentService $studentService, ProgressionService $progressionService) {
        parent::__construct($sequenceTable);
        $this->pathwayService = $pathwayService;
        $this->planService = $planService;
        $this->stepService = $stepService;
        $this->pathwayPlanService = $pathwayPlanService;
        $this->planStepService = $planStepService;
        $this->studentStepService = $studentStepService;
        $this->resourceService = $resourceService;
        $this->studentService = $studentService;
        $this->progressionService = $progressionService;
    }

    public function create($data) {

        $sequence = new Sequence();

        $sequence->create($data);

        $sequence = $this->table->save($sequence);

        return $sequence;

    }

    public function importSequencesFromFile($filename) {

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

            $sequenceDefaultCode = null;
            $sequence1Code = null;
            $sequence2Code = null;
            $sequence3Code = null;
            $sequence4Code = null;
            $sequence5Code = null;

            // EXTRACT ROW DATA
            foreach ($cellIterator as $cell) {

                switch ($cell->getColumn()) {
                    case $STUDENT_FNAME_COL: $studentFName = $cell->getValue();
                        break;
                    case $STUDENT_LNAME_COL: $studentLName = $cell->getValue();
                        break;
                    case $STUDENT_NUM_COL:  $studentNumber = $cell->getValue();
                        break;
                    case $SEQUENCE_DEFAULT: $sequenceDefaultCode = strtolower($cell->getValue());
                        break;
                    case $SEQUENCE_1:       $sequence1Code = strtolower($cell->getValue());
                        break;
                    case $SEQUENCE_2:       $sequence2Code = strtolower($cell->getValue());
                        break;
                    case $SEQUENCE_3:       $sequence3Code = strtolower($cell->getValue());
                        break;
                    case $SEQUENCE_4:       $sequence4Code = strtolower($cell->getValue());
                        break;
                    case $SEQUENCE_5:       $sequence5Code = strtolower($cell->getValue());
                        break;
                    default:                break;
                }
            }

            // FIND STUDENT
            $student = $this->studentService->getWithStudentNumber($studentNumber);

            // BUILD OUT SEQUENCES
            if ($student) {

                // COMMON SEQUENCE DATA
                $newSequenceData = array('student_id' => $student->id, 'plan_groups' => 0);

                if ($sequenceDefaultCode) {
                    $this->inactivateDefaultSequences($student);
                    $defaultSequenceData = $newSequenceData; // DUPLICATE DATA
                    $defaultSequenceData['is_default'] = true;
                    $sequence = $this->create($defaultSequenceData);
                    $this->assignSteps($sequence, $sequenceDefaultCode, $student);
                }

                if ($sequence1Code) {
                    $sequence = $this->create($newSequenceData);
                    $this->assignSteps($sequence, $sequence1Code, $student);
                }
                if ($sequence2Code) {
                    $sequence = $this->create($newSequenceData);
                    $this->assignSteps($sequence, $sequence2Code, $student);
                }
                if ($sequence3Code) {
                    $sequence = $this->create($newSequenceData);
                    $this->assignSteps($sequence, $sequence3Code, $student);
                }
                if ($sequence4Code) {
                    $sequence = $this->create($newSequenceData);
                    $this->assignSteps($sequence, $sequence4Code, $student);
                }
                if ($sequence5Code) {
                    $sequence = $this->create($newSequenceData);
                    $this->assignSteps($sequence, $sequence5Code, $student);
                }

            } else {
                // TODO LOG ERROR OR THROW ERROR
            }

        }

    }

    public function assignSteps(Sequence $sequence, $shortCode, $student) {

        if (isset($this->pathwayCache[$shortCode])) {

            $pathway = $this->pathwayCache[$shortCode];
            $pathwayPlans = $this->pathwayPlanService->getPathwayPlan($pathway);

            $planGroup = 1;

            foreach ($pathwayPlans as $pathwayPlan) {

                $planSteps = $this->planStepService->getPlanSteps($pathwayPlan->plan);

                foreach ($planSteps as $planStep) {

                    $step = $planStep->step;

                    // CREATE STEPS
                    $this->studentStepService->create(array(
                        'step_order' => 1,
                        'student_id' => $student->id,
                        'sequence_id' => $sequence->id,
                        'pathway_id' => $pathway->id,
                        'plan_id' => $pathwayPlan->plan->id,
                        'step_id' => $step->id,
                        'plan_group' => $planGroup,
                    ));

                }

                $planGroup++;

            }

            // SAVE SEQUENCES PLAN GROUP COUNT
            $sequence->planGroups = $planGroup-1; // COUNTER IS ONE AHEAD OF TOTAL AT THIS POINT
            $sequence = $this->save($sequence);

        } else if (isset($this->planCache[$shortCode])) {

            $plan = $this->planCache[$shortCode];

            $planSteps = $this->planStepService->getPlanSteps($plan);

            $planGroup = 1;

            foreach ($planSteps as $planStep) {

                $step = $planStep->step;

                // CREATE STEPS
                $this->studentStepService->create(array(
                    'step_order' => 1,
                    'student_id' => $student->id,
                    'sequence_id' => $sequence->id,
                    'pathway_id' => null,
                    'plan_id' => $plan->id,
                    'step_id' => $step->id,
                    'plan_group' => $planGroup,
                ));

            }

            // SAVE SEQUENCES PLAN GROUP COUNT
            $sequence->planGroups = $planGroup;
            $sequence = $this->save($sequence);

        }



    }

    public function getStudentSequenceOverview(Student $student) {

        //SELECT s.first_name, s.last_name, s.number, pw.short_code AS 'Pathway', pl.short_code AS 'Plan', st.name AS 'Step'
        //FROM student_steps AS ss
        //INNER JOIN students AS s ON s.id = ss.student_id
        //INNER JOIN plans AS pl ON ss.plan_id = pl.id
        //INNER JOIN steps AS st ON ss.step_id = st.id
        //LEFT JOIN pathways AS pw ON ss.pathway_id = pw.id


        $sql = new Sql($this->adapter);
        $select = $sql->select(array());
        $select->from(array('ss' => 'student_steps'))
            ->join(array('s'=> 'students'),  's.id = ss.student_id', array('student_first_name' => 'first_name', 'student_last_name' => 'last_name', 'student_number' => 'number'))
            ->join(array('sq' => 'sequences'), 'ss.sequence_id = sq.id', array('sequence_id' => 'id', 'sequence_is_default' => 'is_default'))
            ->join(array('pl'=> 'plans'),  'ss.plan_id = pl.id', array('plan_name' => 'name', 'plan_short_code' => 'short_code'))
            ->join(array('st'=> 'steps'),  'ss.step_id = st.id', array('step_short_code' => 'short_code'))
            ->join(array('pw'=> 'pathways'),  'ss.pathway_id = pw.id', array('pathway_name' => 'name', 'pathway_short_code' => 'short_code'), \Zend\Db\Sql\Select::JOIN_LEFT)
//            ->where('ss.is_default = 0')
            ->order(array('sq.id ASC', 'ss.id'))
        ;

        $select->where(array('ss.student_id' => $student->id));

        $selectString = $sql->getSqlStringForSqlObject($select);
        $results = $this->adapter->query($selectString, Adapter::QUERY_MODE_EXECUTE);

        return iterator_to_array($results);

    }

    public function setDbAdapter(Adapter $adapter) {
        $this->adapter = $adapter;
    }

    public function inactivateDefaultSequences(Student $student) {

        $where = new \Zend\Db\Sql\Where();

        $where->equalTo('student_id', $student->id)
            ->equalTo('is_default', 1);

        return $this->table->update(array('is_default' => 0), $where);

    }

    public function getStudentSequenceContainerFor(Student $student, \DateTime $date) {

        $date->setTime(0, 0, 0); // JUST TO BE SURE WE ARE ONLY COMPARING DATES

        // TODO HANDLE COMPLETED DEFAULT SEQUENCE (LOOP BACK)

        // FIND INCOMPLETE & NOT DEFAULT
        $sequence = $this->getCurrentSequence($student);

        if (!$sequence) {
            $sequence = $this->getStudentsDefaultSequence($student);
        }

        $progression = $this->getCurrentProgressionForSequence($sequence, $date);

        if (!$progression) {
            // TODO RETURN null????
            // TODO OR ONLY LOAD STEPS *IF* THERE IS A PROGRESSION RETURNED
        }

        $steps = $this->getStepsInPlanGroup($sequence, $progression->planGroup);

        // POPULATE CONTAINER
        $container = new Container();

        foreach ($steps as $step) {
            $container->addStep($step);
        }

        // TODO MOVE CONTAINER'S TO NEXT INCOMPLETE STEP

        return $container;

    }

    public function getCurrentSequence(Student $student) {

        $select = $this->table->getSql()->select();
        $select->where(array(
            'student_id = ?' => $student->id,
            'is_complete = 0',
            'is_default = 0',
        ))->order('id ASC')->limit(1);

        $results = $this->table->fetchWith($select);

        $sequence = $results->current();

        return $sequence;

    }

    public function getCurrentProgressionForSequence(Sequence $sequence, \DateTime $date) {

        // FIND CURRENT PROGRESSION
        $select = $this->progressionService->table->getSql()->select();

        // - DOES is_complete MATTER HERE?
        $select->where(array(
            'sequence_id' => $sequence->id,
        ))->order(array('activity_date DESC'))->limit(1);

        $results = $this->progressionService->table->fetchWith($select);

        $progression = $results->current();

        if (!$progression) {
            // CREATE NEW PROGRESSION
            $progression = $this->progressionService->create(array(
                'sequence_id' => $sequence->id,
                // 'plan_id' ???
                'activity_date' => $date->format('Y-m-d'),
                'plan_group' => 1,
                'is_complete' => 0,
            ));

        } else if ($date > $progression->activityDate) {

            $nextPlanGroup = $progression->planGroup + 1;

            if ($nextPlanGroup <= $sequence->planGroups) {

                // MOVE TO PLAN GROUP ON NEW DAYS, IF THERE IS ANOTHER DAY
                $progression = $this->progressionService->create(array(
                    'sequence_id' => $sequence->id,
                    // 'plan_id' ???
                    'activity_date' => $date->format('Y-m-d'),
                    'plan_group' => $nextPlanGroup,
                    'is_complete' => 0,
                ));
            } else {

                // THERE AREN'T ANY MORE PLAN GROUPS TO DO
                $progression = null;
            }
        }

        return $progression;
    }

    public function getStudentsDefaultSequence(Student $student) {

        $select = $this->table->getSql()->select();
        $select->where(array(
                'student_id = ?' => $student->id,
                'is_default = 1'
            ))->limit(1);

        $results = $this->table->fetchWith($select);

        if (count($results)) {
            $objects = iterator_to_array($results);
            return $objects[0];
        } else {
            return null;
        }
    }

    protected function getStepsInPlanGroup($sequence, $planGroup) {

        // QUERY STUDENT STEPS
        $select = $this->studentStepService->table->getSql()->select();
        $select->where(array(
            'sequence_id = ?' => $sequence->id,
            'plan_group = ?' => $planGroup,
        ))->order(array('id ASC')); // TODO SHOULD BE sequences.step_order

        $results = iterator_to_array($this->studentStepService->table->fetchWith($select));

        // FETCH STEPS
        $steps = array();

        foreach ($results as $row) {
            echo 'STUDENT STEP: ' . $row->id . ', ' . $row->stepId . '<br>';
            $step = $this->stepService->get($row->stepId);
            $step->resource= $this->resourceService->get($step->resourceId);
            $steps[] = $step;
        }

        return $steps;

    }

}