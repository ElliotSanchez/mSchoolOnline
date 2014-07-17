<?php

namespace Admin\ThinkThroughMath\Overview;

use Admin\ModelAbstract\EntityAbstract;

class Entity extends EntityAbstract {

    public $importFilename;
    public $importedAt;
    public $downloadDate;
    public $studentId;

    public $student;
    public $placementTestResult;
    public $lessonsAttempted;
    public $totalLessonsPassed;
    public $targetLessonPassRate;
    public $precursorLessonPassRate;
    public $lessonsPassedByPreQuiz;
    public $preQuizAvg;
    public $postQuizAvg;
    public $problemsAttempted;
    public $earnedPoints;
    public $learningCoachHelps;
    public $liveHelps;
    public $totalMathTime;
    public $schoolTime;
    public $eveningWeekendTime;

    public function filename() {
        return basename($this->importFilename);
    }

    public function create($data) {

        parent::create($data);

        $this->importFilename = (!empty($data['import_filename'])) ? $data['import_filename'] : null;
        $this->importedAt = (!empty($data['imported_at'])) ? new \DateTime($data['imported_at']) : null;
        $this->downloadDate = (!empty($data['download_date'])) ? new \DateTime($data['download_date']) : null;
        $this->studentId = (!empty($data['student_id'])) ? $data['student_id'] : null;

        $this->student = (!empty($data['student'])) ? $data['student'] : null;
        $this->placementTestResult = (!empty($data['placement_test_result'])) ? $data['placement_test_result'] : null;
        $this->lessonsAttempted = (!empty($data['lessons_attempted'])) ? $data['lessons_attempted'] : null;
        $this->totalLessonsPassed = (!empty($data['total_lessons_passed'])) ? $data['total_lessons_passed'] : null;
        $this->targetLessonPassRate = (!empty($data['target_lesson_pass_rate'])) ? $data['target_lesson_pass_rate'] : null;
        $this->precursorLessonPassRate = (!empty($data['precursor_lesson_pass_rate'])) ? $data['precursor_lesson_pass_rate'] : null;
        $this->lessonsPassedByPreQuiz = (!empty($data['lessons_passed_by_pre_quiz'])) ? $data['lessons_passed_by_pre_quiz'] : null;
        $this->preQuizAvg = (!empty($data['pre_quiz_avg'])) ? $data['pre_quiz_avg'] : null;
        $this->postQuizAvg = (!empty($data['post_quiz_avg'])) ? $data['post_quiz_avg'] : null;
        $this->problemsAttempted = (!empty($data['problems_attempted'])) ? $data['problems_attempted'] : null;
        $this->earnedPoints = (!empty($data['earned_points'])) ? $data['earned_points'] : null;
        $this->learningCoachHelps = (!empty($data['learning_coach_helps'])) ? $data['learning_coach_helps'] : null;
        $this->liveHelps = (!empty($data['live_helps'])) ? $data['live_helps'] : null;
        $this->totalMathTime = (!empty($data['total_math_time'])) ? $data['total_math_time'] : null;
        $this->schoolTime = (!empty($data['school_time'])) ? $data['school_time'] : null;
        $this->eveningWeekendTime = (!empty($data['evening_weekend_time'])) ? $data['evening_weekend_time'] : null;

    }

    public function exchangeArray(array $data)
    {

        if (!$this->id) $this->id = (isset($data['id']) && !empty($data['id'])) ? $data['id'] : null;

        $this->importFilename = (!empty($data['import_filename'])) ? $data['import_filename'] : null;
        $this->importedAt = (!empty($data['imported_at'])) ? new \DateTime($data['imported_at']) : null;
        $this->downloadDate = (!empty($data['download_date'])) ? new \DateTime($data['download_date']) : null;
        $this->studentId = (!empty($data['student_id'])) ? $data['student_id'] : null;

        $this->student= (!empty($data['student'])) ? $data['student'] : $this->student;
        $this->placementTestResult = (!empty($data['placement_test_result'])) ? $data['placement_test_result'] : $this->placementTestResult;
        $this->lessonsAttempted = (!empty($data['lessons_attempted'])) ? $data['lessons_attempted'] : $this->lessonsAttempted;
        $this->totalLessonsPassed = (!empty($data['total_lessons_passed'])) ? $data['total_lessons_passed'] : $this->totalLessonsPassed;
        $this->targetLessonPassRate = (!empty($data['target_lesson_pass_rate'])) ? $data['target_lesson_pass_rate'] : $this->targetLessonPassRate;
        $this->precursorLessonPassRate = (!empty($data['precursor_lesson_pass_rate'])) ? $data['precursor_lesson_pass_rate'] : $this->precursorLessonPassRate;
        $this->lessonsPassedByPreQuiz = (!empty($data['lessons_passed_by_pre_quiz'])) ? $data['lessons_passed_by_pre_quiz'] : $this->lessonsPassedByPreQuiz;
        $this->preQuizAvg = (!empty($data['pre_quiz_avg'])) ? $data['pre_quiz_avg'] : $this->preQuizAvg;
        $this->postQuizAvg = (!empty($data['post_quiz_avg'])) ? $data['post_quiz_avg'] : $this->postQuizAvg;
        $this->problemsAttempted = (!empty($data['problems_attempted'])) ? $data['problems_attempted'] : $this->problemsAttempted;
        $this->earnedPoints = (!empty($data['earned_points'])) ? $data['earned_points'] : $this->earnedPoints;
        $this->learningCoachHelps = (!empty($data['learning_coach_helps'])) ? $data['learning_coach_helps'] : $this->learningCoachHelps;
        $this->liveHelps = (!empty($data['live_helps'])) ? $data['live_helps'] : $this->liveHelps;
        $this->totalMathTime = (!empty($data['total_math_time'])) ? $data['total_math_time'] : $this->totalMathTime;
        $this->schoolTime = (!empty($data['school_time'])) ? $data['school_time'] : $this->schoolTime;
        $this->eveningWeekendTime = (!empty($data['evening_weekend_time'])) ? $data['evening_weekend_time'] : $this->eveningWeekendTime;

        $this->exchangeDates($data);
    }

    public function toData() {

        $data = array(
            'import_filename' => $this->importFilename,
            'imported_at' => ($this->importedAt) ? ($this->importedAt->format('Y-m-d H:i:s')) : (null),
            'download_date' => ($this->downloadDate) ? ($this->downloadDate->format('Y-m-d H:i:s')) : (null),
            'student_id' => $this->studentId,
            'student' => $this->student,
            'placement_test_result' => $this->placementTestResult,
            'lessons_attempted' => $this->lessonsAttempted,
            'total_lessons_passed' => $this->totalLessonsPassed,
            'target_lesson_pass_rate' => $this->targetLessonPassRate,
            'precursor_lesson_pass_rate' => $this->precursorLessonPassRate,
            'lessons_passed_by_pre_quiz' => $this->lessonsPassedByPreQuiz,
            'pre_quiz_avg' => $this->preQuizAvg,
            'post_quiz_avg' => $this->postQuizAvg,
            'problems_attempted' => $this->problemsAttempted,
            'earned_points' => $this->earnedPoints,
            'learning_coach_helps' => $this->learningCoachHelps,
            'live_helps' => $this->liveHelps,
            'total_math_time' => $this->totalMathTime,
            'school_time' => $this->schoolTime,
            'evening_weekend_time' => $this->eveningWeekendTime,

        );

        return $data;

    }

    public function getArrayCopy() {
        return $this->toData();
    }

}