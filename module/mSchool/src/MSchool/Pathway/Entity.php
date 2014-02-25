<?php

namespace MSchool\Pathway;

use Admin\ModelAbstract\ServiceAbstract as ServiceAbstract;
use Admin\ModelAbstract\EntityAbstract;

class Entity extends EntityAbstract {

    public $pathwayDate;
    public $step;
    public $timer;
    public $isActive;
    public $uploadSetId;

    public $studentId;
    public $resourceId;

    public function create($data) {

        parent::create($data);
        $this->pathwayDate = (!empty($data['pathway_date'])) ? (new \DateTime($data['pathway_date'])) : null;
        $this->step = (!empty($data['step'])) ? $data['step'] : null;
        $this->timer = (!empty($data['timer'])) ? $data['timer'] : null;
        $this->isActive = (!empty($data['is_active'])) ? $data['is_active'] : null;
        $this->uploadSetId = (!empty($data['upload_set_id'])) ? $data['upload_set_id'] : null;

        $this->studentId = (!empty($data['student_id'])) ? $data['student_id'] : null;
        $this->resourceId = (!empty($data['resource_id'])) ? $data['resource_id'] : null;

    }

    public function exchangeArray(array $data)
    {

        if (!$this->id) $this->id = (isset($data['id']) && !empty($data['id'])) ? $data['id'] : null;

        $this->pathwayDate = (!empty($data['pathway_date'])) ? (new \DateTime($data['pathway_date'])) : null;
        $this->step = (!empty($data['step'])) ? $data['step'] : null;
        $this->timer = (!empty($data['timer'])) ? $data['timer'] : null;
        $this->isActive = (!empty($data['is_active'])) ? $data['is_active'] : null;
        $this->uploadSetId = (!empty($data['upload_set_id'])) ? $data['upload_set_id'] : null;

        $this->studentId = (!empty($data['student_id'])) ? $data['student_id'] : null;
        $this->resourceId = (!empty($data['resource_id'])) ? $data['resource_id'] : null;

        $this->exchangeDates($data);
    }

    public function toData() {

        $data = array(
            'student_id' => $this->studentId,
            'resource_id' => $this->resourceId,
            'pathway_date' => ($this->pathwayDate instanceof \DateTime) ? ($this->pathwayDate->format('Y-m-d')) : (null),
            'step' => $this->step,
            'timer' => $this->timer,
            'is_active' => $this->isActive,
            'upload_set_id' => $this->uploadSetId,
        );

        return $data;

    }

    public function getArrayCopy() {
        return $this->toData();
    }

}