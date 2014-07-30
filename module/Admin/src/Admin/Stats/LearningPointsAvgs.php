<?php

namespace Admin\Stats;

class LearningPointsAvgs
{
    protected $data;

    public function __construct() {

        $this->data = [];

    }

    public function add($gradeLevelId, $gradeLevelName, $avg) {

//        if (!isset($this->data[$studentId])) {
            $this->data[$gradeLevelId] = [
              'grade_level_id' => $gradeLevelId,
              'grade_level_name' => $gradeLevelName,
              'avg' => $avg,
            ];
//        }

        //$this->data[$studentId]['learning_points'] += $lps;

    }

    public function getData() {

        $data = new \stdClass();

        $data->assessment = "1";
        $data->data = array_values($this->data);

        return [$data];
    }

}