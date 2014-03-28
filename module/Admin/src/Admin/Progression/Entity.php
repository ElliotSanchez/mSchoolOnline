<?php

namespace Admin\Progression;

use Admin\ModelAbstract\EntityAbstract;

class Entity extends EntityAbstract {

    public $sequenceId;
    public $planId;
    public $activityDate;
    public $planGroup;
    public $isComplete;

    public function create($data) {

        parent::create($data);
        print_r($data);
        $this->sequenceId = (!empty($data['sequence_id'])) ? $data['sequence_id'] : null;
        $this->planId = (!empty($data['plan_id'])) ? $data['plan_id'] : null;
        $this->activityDate = (!empty($data['activity_date'])) ? (new \DateTime($data['activity_date'] . ' 00:00:00')) : (null);
        $this->planGroup = (!empty($data['plan_group'])) ? ($data['plan_group']) : (null);
        $this->isComplete = (bool) (!empty($data['is_complete'])) ? $data['is_complete'] : false;

    }

    public function exchangeArray(array $data)
    {

        if (!$this->id) $this->id = (isset($data['id']) && !empty($data['id'])) ? $data['id'] : null;

        $this->sequenceId = (!empty($data['sequence_id'])) ? $data['sequence_id'] : null;
        $this->planId = (!empty($data['plan_id'])) ? $data['plan_id'] : null;
        $this->activityDate = (!empty($data['activity_date'])) ? (new \DateTime($data['activity_date'] . ' 00:00:00')) : (null);
        $this->planGroup = (!empty($data['plan_group'])) ? ($data['plan_group']) : (null);
        $this->isComplete = (bool) (!empty($data['is_complete'])) ? $data['is_complete'] : false;

        $this->exchangeDates($data);
    }

    public function toData() {

        $data = array(
            'sequence_id' => $this->sequenceId,
            'plan_id' => $this->planId,
            'activity_date' => ($this->activityDate instanceof \DateTime) ? ($this->activityDate->format('Y-m-d')) : (null),
            'plan_group' => $this->planGroup,
            'is_complete' => (int) $this->isComplete,
        );

        return $data;

    }

    public function getArrayCopy() {
        return $this->toData();
    }

}