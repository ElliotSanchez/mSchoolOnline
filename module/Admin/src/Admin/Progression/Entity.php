<?php

namespace Admin\Progression;

use Admin\ModelAbstract\EntityAbstract;

class Entity extends EntityAbstract {

    public $studentId;
    public $sequenceId;
    public $planId;
    public $activityDate;
    public $planGroup;
    public $isComplete;
    public $completedAt;
    public $isActive;

    public function create($data) {

        parent::create($data);
        //print_r($data);
        $this->studentId = (!empty($data['student_id'])) ? $data['student_id'] : null;
        $this->sequenceId = (!empty($data['sequence_id'])) ? $data['sequence_id'] : null;
        $this->planId = (!empty($data['plan_id'])) ? $data['plan_id'] : null;
        $this->activityDate = (!empty($data['activity_date'])) ? (new \DateTime($data['activity_date'] . ' 00:00:00')) : (null);
        $this->planGroup = (!empty($data['plan_group'])) ? ($data['plan_group']) : (null);
        $this->isComplete = (bool) (!empty($data['is_complete'])) ? $data['is_complete'] : false;
        $this->completedAt = (!empty($data['completed_at'])) ? (new \DateTime($data['completed_at'])) : null;
        $this->isActive = (bool) (!empty($data['is_active'])) ? $data['is_complete'] : 1;

    }

    public function exchangeArray(array $data)
    {

        if (!$this->id) $this->id = (isset($data['id']) && !empty($data['id'])) ? $data['id'] : null;

        $this->studentId = (!empty($data['student_id'])) ? $data['student_id'] : null;
        $this->sequenceId = (!empty($data['sequence_id'])) ? $data['sequence_id'] : null;
        $this->planId = (!empty($data['plan_id'])) ? $data['plan_id'] : null;
        $this->activityDate = (!empty($data['activity_date'])) ? (new \DateTime($data['activity_date'] . ' 00:00:00')) : (null);
        $this->planGroup = (!empty($data['plan_group'])) ? ($data['plan_group']) : (null);
        $this->isComplete = (bool) (!empty($data['is_complete'])) ? $data['is_complete'] : false;
        $this->completedAt = (!empty($data['completed_at'])) ? (new \DateTime($data['completed_at'])) : null;
        $this->isActive = (bool) (!empty($data['is_active'])) ? $data['is_complete'] : $this->isActive;

        $this->exchangeDates($data);
    }

    public function toData() {

        $data = array(
            'student_id' => $this->studentId,
            'sequence_id' => $this->sequenceId,
            'plan_id' => $this->planId,
            'activity_date' => ($this->activityDate instanceof \DateTime) ? ($this->activityDate->format('Y-m-d')) : (null),
            'plan_group' => $this->planGroup,
            'is_complete' => (int) $this->isComplete,
            'completed_at' => ($this->completedAt instanceof \DateTime) ? ($this->completedAt->format('Y-m-d H:i:s')) : (null),
            'is_active' => (int) $this->isActive,
        );

        return $data;

    }

    public function getArrayCopy() {
        return $this->toData();
    }

    public function markComplete() {
        $this->isComplete = true;
        $this->completedAt = new \DateTime();
    }

}