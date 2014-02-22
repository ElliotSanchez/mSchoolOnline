<?php

namespace MSchool\Pathway;

class Container
{

    public $currStep;

    protected $steps;

    public function __construct() {
        $this->currStep = 1;
        $this->steps = array();
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
        return ($this->currStep == count($this->steps));
    }

    public function addStep($step) {
        $this->steps[] = $step;
    }

    public function getCurrentStep() {
        return $this->steps[$this->currStep-1];
    }

    public function reset() {
        $this->currStep = 1;
    }

    public function getPercentComplete() {
        $numSteps = count($this->steps);

        return ($numSteps > 0) ? (round($this->currStep / count($this->steps) * 100, 0) ) : (0);
    }

}