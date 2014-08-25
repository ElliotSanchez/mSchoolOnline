<?php

namespace MSchool\Pathway;

use Admin\Sequence\Entity as Sequence;
use Admin\Progression\Entity as Progression;
use Admin\Resource\Entity as Resource;

class Container
{

    public $currStep;

    protected $sequence;
    protected $currentProgression;
    protected $studentSteps;

    protected $extraCreditResource;

    public function __construct(Sequence $sequence = null, Progression $progression = null) {
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

    public function fastForward() {

        foreach ($this->studentSteps as $studentStep) {
            if ($studentStep->isComplete) {
                $this->currStep++;
            }
        }

        if ($this->currStep > count($this->studentSteps)) {
            $this->currStep = count($this->studentSteps);
        }

    }

    public function addStudentStep($step) {
        $this->studentSteps[] = $step;
    }

    public function getCurrentStudentStep() {
        return $this->studentSteps[$this->currStep-1];
    }

    public function getCurrentStep() {
        // TODO DON'T ALLOW NEGATIVE INDEXES
        return $this->studentSteps[$this->currStep-1]->step;
    }

    public function getRemainingStudentSteps() {

        $remaining = [];

        if ($this->currStep != null) {
            for ($i = $this->currStep; $i < count($this->studentSteps); $i++) {
                $remaining[] = $this->studentSteps[$i];
            }
        }

        return $remaining;

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

    public function isInvalid() {
        return (bool) $this->sequence;
    }

    public function hasAvailableSteps() {
        return (bool) $this->currentProgression && !$this->currentProgression->wasSkipped();
    }

    public function generateActivity() {

        $activity = new Activity();

        if ($this->isExtraCreditWork()) {

            $activity->setResource($this->getExtraCreditResource());

        } else {

            $step = $this->getCurrentStep();

            $activity->setResource($step->resource);

            if ($step->isTimed()) {
                $activity->setTimer($step->timer);
                $activity->setShowPopup((bool)$step->showPopup);
            }

        }

        return $activity;

    }

    // EXTRA CREDIT
    public function setExtraCreditResource(Resource $resource) {
        $this->extraCreditResource = $resource;
    }

    public function getExtraCreditResource() {
        return $this->extraCreditResource;
    }

    public function isExtraCreditWork() {
        return (bool) $this->extraCreditResource;
    }

}