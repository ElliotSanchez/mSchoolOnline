<?php

namespace MSchool\Pathway;

use Admin\Sequence\Entity as Sequence;
use Admin\Progression\Entity as Progression;

class Container
{

    public $currStep;

    protected $sequence;
    protected $currentProgression;
    protected $studentSteps;

    public function __construct(Sequence $sequence, Progression $progression) {
        $this->currStep = 1;
        $this->studentSteps = array();
        $this->sequence = $sequence;
        $this->currentProgression = $progression;
    }

    public function next() {
        if (!$this->isAtLastStep())
            $this->currStep++;
    }

    public function previous() {
        if (!$this->isAtFirstStep())
            $this->currStep--;
    }

    public function isAtFirstStep() {
        return ($this->currStep == 1);
    }

    public function isAtLastStep() {
        return ($this->currStep == count($this->studentSteps));
    }

    public function addStudentStep($step) {
        $this->studentSteps[] = $step;
    }

    public function getCurrentStudentStep() {
        return $this->studentSteps[$this->currStep-1];
    }

    public function getCurrentStep() {
        return $this->studentSteps[$this->currStep-1]->step;
    }

    public function reset() {
        $this->currStep = 1;
    }

    public function numberOfSteps() {
        return count($this->studentSteps);
    }

    public function getPercentComplete() {
        $numSteps = count($this->studentSteps);

        return ($numSteps > 0) ? (round($this->currStep / count($this->studentSteps) * 100, 0) ) : (0);
    }

    public function getSequence() {
        return $this->sequence;
    }

    public function getCurrentProgression() {
        return $this->currentProgression;
    }

    public function isLastPlanGroup() {
        return $this->sequence->planGroups == $this->currentProgression->planGroup;
    }

}