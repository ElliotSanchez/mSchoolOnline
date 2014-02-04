<?php

namespace Admin\ModelAbstract;

abstract class ServiceAbstract
{

    protected $table;

    abstract public function create($data);

    public function __construct(TableAbstract $table) {
        $this->table = $table;
    }

    public function get($id) {
        return $this->table->get($id);
    }

    public function save(EntityAbstract $model) {
        return $this->table->save($model);
    }

    public function all() {
        return $this->table->fetchAll();
    }

}