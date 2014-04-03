<?php

namespace MSchool\Pathway;

class Container
{

    public $currStep;

    protected $studentSteps;

    public function __construct() {
        $this->currStep = 1;
        $this->studentSteps = array();
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

}