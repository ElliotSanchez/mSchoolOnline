<?php

namespace Admin\ModelAbstract;

abstract class EntityAbstract
{

    public $id;
    public $createdAt;
    public $updatedAt;

    abstract public function exchangeArray(array $data);

    abstract public function toData();

    protected function create($data) {
        $this->id = (!empty($data['id'])) ? $data['id'] : null;
        $this->created_at = (!empty($data['created_at'])) ? new \DateTime($data['created_at']) : null;
        $this->updated_at = (!empty($data['updated_at'])) ? new \DateTime($data['updated_at']) : null;
    }

    public function setCreatedDate() {
        $this->createdAt = new \DateTime();
    }

    public function setUpdatedDate() {
        $this->updatedAt = new \DateTime();
    }

    public function exchangeDates(array $data) {

        $this->createdAt = (!empty($data['created_at'])) ? (new \DateTime($data['created_at'])) : null;
        $this->updatedAt = (!empty($data['updated_at'])) ? (new \DateTime($data['updated_at'])) : null;

    }

}