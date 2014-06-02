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
use Admin\StudentStep\Entity as StudentStep;
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
        $REPLACE_EXISTING = 'D';
        $SEQUENCE_DEFAULT = 'E';
        $SEQUENCE_1 = 'F';
        $SEQUENCE_2 = 'G';
        $SEQUENCE_3 = 'H';
        $SEQUENCE_4 = 'I';
        $SEQUENCE_5 = 'J';

        foreach ($rowIterator as $row) {

            $cellIterator = $row->getCellIterator();
            $cellIterator->setIterateOnlyExistingCells(false); // Loop all cells, even if it is not set

            if(1 == $row->getRowIndex ()) continue; //skip first row

            $replaceExisting = false;
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
                    case $REPLACE_EXISTING: $replaceExisting = $cell->getValue();
                                            $replaceExisting = (strtolower($replaceExisting) == "yes") ? (true) : (false);
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

                if ($replaceExisting) {
                    $this->inactivateSequences($student);
                }

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
        $select = $sql->select(array())->columns(array('ss_is_complete' => 'is_complete', 'ss_completed_at' => 'completed_at', 'plan_group' => 'plan_group'));
        $select->from(array('ss' => 'student_steps'))
            ->join(array('s'=> 'students'),  's.id = ss.student_id', array('student_first_name' => 'first_name', 'student_last_name' => 'last_name', 'student_number' => 'number'))
            ->join(array('sq' => 'sequences'), 'ss.sequence_id = sq.id', array('sequence_id' => 'id', 'sequence_is_default' => 'is_default'))
            ->join(array('pl'=> 'plans'),  'ss.plan_id = pl.id', array('plan_name' => 'name', 'plan_short_code' => 'short_code'))
            ->join(array('st'=> 'steps'),  'ss.step_id = st.id', array('step_short_code' => 'short_code'))
            ->join(array('pw'=> 'pathways'),  'ss.pathway_id = pw.id', array('pathway_name' => 'name', 'pathway_short_code' => 'short_code'), \Zend\Db\Sql\Select::JOIN_LEFT)
//            ->where('ss.is_default = 0')
            ->order(array('sq.is_default ASC', 'sq.id ASC', 'ss.id'))
        ;

        $select->where(array('ss.student_id' => $student->id));

        $selectString = $sql->getSqlStringForSqlObject($select);
        $results = $this->adapter->query($selectString, Adapter::QUERY_MODE_EXECUTE);

        return iterator_to_array($results);

    }

    public function setDbAdapter(Adapter $adapter) {
        $this->adapter = $adapter;
    }

    // INACTIVATE
    public function inactivateDefaultSequences(Student $student) {

        $where = new \Zend\Db\Sql\Where();

        $where->equalTo('student_id', $student->id)
            ->equalTo('is_default', 1);

        return $this->table->update(array('is_default' => 0), $where);

    }

    protected function inactivateSequences(Student $student) {

        $where = new \Zend\Db\Sql\Where();

        $where->equalTo('student_id', $student->id)
            ->equalTo('is_complete', 0);

        return $this->table->update(array('is_active' => 0), $where);

    }

    // SEQUENCE CONTAINER
    public function getStudentSequenceContainerFor(Student $student, \DateTime $date) {

        $date->setTime(0, 0, 0); // JUST TO BE SURE WE ARE ONLY COMPARING DATES

        // TODO HANDLE COMPLETED DEFAULT SEQUENCE (LOOP BACK)

        $sequence = null;
        $progression = null;
        $container = null;

        // IS THERE PROGRESSION TODAY?
        $todaysProgression = $this->getProgressionForDate($student, $date);
        if ($todaysProgression && $todaysProgression->isComplete) {

            // ALL DONE FOR THE DAY
            $sequence = null;
            $progression = null;

        } else if ($todaysProgression && $todaysProgression->activityDate == $date) {

            // KEEP WORKING ON THE SAME STUFF
            $progression = $todaysProgression;
            $sequence = $this->get($progression->sequenceId);

        } else {
            // GET THE RIGHT SEQUENCE AND PROGRESSION

            // IF WE NEED THE DEFAULT SEQUENCE START WITH IT
            $defaultSequence = $this->getDefaultSequenceIfNeeded($student);

            if ($defaultSequence) {
                $sequence = $defaultSequence;
            } else {
                // OTHER WISE FIND THE RIGHT ONE
                $sequence = $this->getCurrentSequence($student);
            }

            if ($sequence) {
                $progression = $this->getCurrentProgressionForSequence($sequence, $date);
            }
        }

        if ($sequence && !$progression) {
            // SEQUENCE EXISTS, BUT NO PLAN GROUPS LEFT (THEY ARE LIKELY INCOMPLETE)
            // MOVE TO ANOTHER SEQUENCES
            $sequence->movedOn = true;

            $sequence = $this->save($sequence);

            $sequence = $this->getCurrentSequence($student);

            $progression = $this->getCurrentProgressionForSequence($sequence, $date);

        }

        // BUILD CONTAINER
        if ($progression) {
            // WITH STEPS
            $studentSteps = $this->getStudentStepsInPlanGroup($sequence, $progression->planGroup);

            // POPULATE CONTAINER
            $container = new Container($sequence, $progression);

            foreach ($studentSteps as $step) {
                $container->addStudentStep($step);
            }

            // MOVE CONTAINER'S TO NEXT INCOMPLETE STEP
            $container->fastForward();
        } else {
            // OR AS EMPTY (i.e. NO WORK LEFT TO BE DONE)
            if ($sequence && $progression) {
                $container = new Container($sequence, $progression);
            }
        }

        return $container;

    }

    public function getDefaultSequenceIfNeeded(Student $student) {

        // TODO THIS IS INEFFICIENT; USE A COUNT CALL
        $select = $this->table->getSql()->select();
        $select->where(array(
            'student_id = ?' => $student->id,
            'is_complete = 0',
            'is_default = 0',
            'is_active = 1',
        ))->order('id ASC');

        $sequences = iterator_to_array($this->table->fetchWith($select));

        if (!count($sequences)) {
            return $this->getStudentsDefaultSequence($student);
        } else {
            return null;
        }

    }

    public function getCurrentSequence(Student $student) {

        // ##########################################################################################
        // IMPORTANT! THIS WILL RETURN THE NEXT SEQUENCE AND PROGRESS EVEN THOSE THE STUDENT HAS MADE PROGRESS TODAY
        // ##########################################################################################

        $select = $this->table->getSql()->select();
        $select->where(array(
            'student_id = ?' => $student->id,
            'is_complete = 0',
            'is_default = 0',
            'is_active = 1',
            'moved_on = 0'
        ))->order('id ASC')->limit(1);

        $results = $this->table->fetchWith($select);

        $sequence = $results->current();

        return $sequence;

    }

    public function getProgressionForDate(Student $student, \DateTime $date) {

        // FIND CURRENT PROGRESSION
        $select = $this->progressionService->table->getSql()->select();

        $select->where(array(
            'student_id' => $student->id,
            'activity_date' => $date->format('Y-m-d'),
        ))->limit(1);

        $results = $this->progressionService->table->fetchWith($select);

        $progression = $results->current();

        return $progression;

    }

    public function getCurrentProgressionForSequence(Sequence $sequence, \DateTime $date) {

        // FIND CURRENT PROGRESSION
        $select = $this->progressionService->table->getSql()->select();

        // - DOES is_complete MATTER HERE?
        $select->where(array(
            'sequence_id' => $sequence->id,
            'activity_date' => $date->format('Y-m-d'),
        ))->order(array('activity_date DESC'))->limit(1);

        $results = $this->progressionService->table->fetchWith($select);

        $progression = $results->current();

        if (!$progression) {

            $previousProgress = $this->getPreviousProgression($sequence);

            if ($previousProgress) {
                //die('>'.__LINE__);
                $newPlanGroup = $previousProgress->planGroup + 1;
            } else {
                $newPlanGroup = 1;
            }

            if ($newPlanGroup <= $sequence->planGroups) {

                // CREATE NEW PROGRESSION
                $progression = $this->progressionService->create(array(
                    'student_id' => $sequence->studentId,
                    'sequence_id' => $sequence->id,
                    // 'plan_id' ???
                    'activity_date' => $date->format('Y-m-d'),
                    'plan_group' => $newPlanGroup, // TODO THIS SHOULD BE SET FROM PREVIOUS DATA, *OR* DEFAULT TO 0; PROBABLY FROM THE SEQUENCE
                    'is_complete' => 0,
                ));

            } else {
                // WHAT SHOULD HAPPEN HERE?
                $progression = null;
            }

        } else if ($date == $progression->activityDate) {

            if ($progression->isComplete) {
                $progression = null; // ALL DONE FOR THE DAY
            }

        } else if ($date > $progression->activityDate) {

            $nextPlanGroup = $progression->planGroup + 1;

            if ($nextPlanGroup <= $sequence->planGroups) {

                // MOVE TO PLAN GROUP ON NEW DAYS, IF THERE IS ANOTHER DAY
                $progression = $this->progressionService->create(array(
                    'student_id' => $sequence->studentId,
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

    public function getPreviousProgression(Sequence $sequence) {

        // FIND CURRENT PROGRESSION
        $select = $this->progressionService->table->getSql()->select();

        // - DOES is_complete MATTER HERE?
        $select->where(array(
            'sequence_id' => $sequence->id,
        ))->order(array('activity_date DESC'))->limit(1);

        $results = $this->progressionService->table->fetchWith($select);

        return $results->current();

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

    protected function getStudentStepsInPlanGroup($sequence, $planGroup) {

        // QUERY STUDENT STEPS
        $select = $this->studentStepService->table->getSql()->select();
        $select->where(array(
            'sequence_id = ?' => $sequence->id,
            'plan_group = ?' => $planGroup,
        ))->order(array('id ASC')); // TODO SHOULD BE sequences.step_order

        $results = iterator_to_array($this->studentStepService->table->fetchWith($select));

        // FETCH STUDENT STEPS
        $studentSteps = array();

        foreach ($results as $currStudentStep) {
            $studentStep = clone $currStudentStep;
            $studentStep->step = $this->stepService->get($studentStep->stepId);
            $studentStep->step->resource = $this->resourceService->get($studentStep->step->resourceId);
            $studentSteps[] = $studentStep;
        }

        return $studentSteps;

    }

    // COMPLETION METHODS

    public function markCurrentStepAsComplete(Container $container) {

        $this->markStudentStepAsComplete($container->getCurrentStudentStep());

        if ($container->isAtLastStep()) {
            // MARK PROGRESSION COMPLETE
            $this->markProgressionAsComplete($container->getCurrentProgression());
            // CHECK SEQUENCE FOR COMPLETION
            if ($container->isLastPlanGroup()) {
                $this->markSequenceComplete($container->getSequence());
            }
        }

    }

    protected function markStudentStepAsComplete(StudentStep $studentStep) {

        $studentStep->markComplete();

        return $this->studentStepService->save($studentStep);

    }

    protected function markProgressionAsComplete(Progression $progression) {

        $progression->markComplete();

        return $this->progressionService->save($progression);

    }

    protected function markSequenceComplete(Sequence $sequence) {

        $sequence->markComplete();

        return $this->save($sequence);

    }

}