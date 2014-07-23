<?php

namespace Admin\Stats;

class LearningPoints
{
    protected $data;

    public function __construct() {

        $this->data = [];

    }

    public function add($studentId, $lastName, $firstName, $lps) {

        if (!isset($this->data[$studentId])) {
            $this->data[$studentId] = [
              'student_id' => $studentId,
              'last_name' => $lastName,
              'first_name' => $firstName,
              'student' => $firstName . ' ' . $lastName,
              'learning_points' => 0,
            ];
        }

        $this->data[$studentId]['learning_points'] += $lps;

    }

    public function getData() {

        return $this->data;
    }

}