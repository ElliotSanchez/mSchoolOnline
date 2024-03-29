<?php

namespace MSchool\Pathway;

class Step
{

    public $resource;
    public $time; // IN SECONDS;

    public function __construct($resource, $time) {
        $this->resource = $resource;
        $this->time = $time;
    }

    public function isTimed() {
        return is_numeric($this->time);
    }

}