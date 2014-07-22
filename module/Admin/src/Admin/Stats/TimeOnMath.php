<?php

namespace Admin\Stats;

class TimeOnMath
{
    protected $data;

    public function __construct() {

        $this->data = [];

    }

    public function add($studentId, $lastName, $firstName, $time) {

        if (!isset($this->data[$studentId])) {
            $this->data[$studentId] = [
              'student_id' => $studentId,
              'last_name' => $lastName,
              'first_name' => $firstName,
              'student' => $firstName . ' ' . $lastName,
              'time' => 0,
            ];
        }

        $this->data[$studentId]['time'] += $time;
        $this->data[$studentId]['time_display'] = gmdate("H:i:s", $this->data[$studentId]['time']);

    }

    public function getData() {
        return $this->data;
    }

}