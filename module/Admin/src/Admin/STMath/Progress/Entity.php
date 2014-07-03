<?php

namespace Admin\STMath\Progress;

use Admin\ModelAbstract\EntityAbstract;

class Entity extends EntityAbstract {

    public $importFilename;
    public $importedAt;
    public $studentId;

    public $scd;
    public $firstName;
    public $lastName;
    public $schoolSessions;
    public $homeSessions;
    public $syllabusProgress;
    public $objectivesAttempted;
    public $standardsMastery;
    public $standardsAttempted;
    public $currentObjective;
    public $module;
    public $game;
    public $level;
    public $totalAttempts;
    public $lastSession;
    public $alerts;
    public $extraPlays;
    public $lowPostQuizScore;
    public $decreasingQuizScore;
    public $lowStandardsMastery;
    public $lowTimeOnTask;
    public $highNumberOfTries;
    public $lowProgress;
    public $lowAttendance;
    public $levelCancelling;
    public $unaddressedHandRaising;
    public $stuckOnIntroObjectives;

    public function filename() {
        return basename($this->importFilename);
    }

    public function create($data) {

        parent::create($data);

        $this->importFilename = (!empty($data['import_filename'])) ? $data['import_filename'] : null;
        $this->importedAt = (!empty($data['imported_at'])) ? new \DateTime($data['imported_at']) : null;
        $this->studentId = (!empty($data['student_id'])) ? $data['student_id'] : null;

        $this->scd = (strlen($data['scd'])) ? ($data['scd']) : null;
        $this->firstName = (strlen($data['first_name'])) ? ($data['first_name']) : null;
        $this->lastName = (strlen($data['last_name'])) ? ($data['last_name']) : null;
        $this->schoolSessions = (strlen($data['school_sessions'])) ? ($data['school_sessions']) : null;
        $this->homeSessions = (strlen($data['home_sessions'])) ? ($data['home_sessions']) : null;
        $this->syllabusProgress = (strlen($data['syllabus_progress'])) ? ($data['syllabus_progress']) : null;
        $this->objectivesAttempted = (strlen($data['objectives_attempted'])) ? ($data['objectives_attempted']) : null;
        $this->standardsMastery = (strlen($data['standards_mastery'])) ? ($data['standards_mastery']) : null;
        $this->standardsAttempted = (strlen($data['standards_attempted'])) ? ($data['standards_attempted']) : null;
        $this->currentObjective = (strlen($data['current_objective'])) ? ($data['current_objective']) : null;
        $this->module = (strlen($data['module'])) ? ($data['module']) : null;
        $this->game = (strlen($data['game'])) ? ($data['game']) : null;
        $this->level = (strlen($data['level'])) ? ($data['level']) : null;
        $this->totalAttempts = (strlen($data['total_attempts'])) ? ($data['total_attempts']) : null;
        $this->lastSession = (strlen($data['last_session'])) ? ($data['last_session']) : null;
        $this->alerts = (strlen($data['alerts'])) ? ($data['alerts']) : null;
        $this->extraPlays = (strlen($data['extra_plays'])) ? ($data['extra_plays']) : null;
        $this->lowPostQuizScore = (strlen($data['low_post_quiz_score'])) ? ($data['low_post_quiz_score']) : null;
        $this->decreasingQuizScore = (strlen($data['decreasing_quiz_score'])) ? ($data['decreasing_quiz_score']) : null;
        $this->lowStandardsMastery = (strlen($data['low_standards_mastery'])) ? ($data['low_standards_mastery']) : null;
        $this->lowTimeOnTask = (strlen($data['low_time_on_task'])) ? ($data['low_time_on_task']) : null;
        $this->highNumberOfTries = (strlen($data['high_number_of_tries'])) ? ($data['high_number_of_tries']) : null;
        $this->lowProgress = (strlen($data['low_progress'])) ? ($data['low_progress']) : null;
        $this->lowAttendance = (strlen($data['low_attendance'])) ? ($data['low_attendance']) : null;
        $this->levelCancelling = (strlen($data['level_cancelling'])) ? ($data['level_cancelling']) : null;
        $this->unaddressedHandRaising = (strlen($data['unaddressed_hand_raising'])) ? ($data['unaddressed_hand_raising']) : null;
        $this->stuckOnIntroObjectives = (strlen($data['stuck_on_intro_objectives'])) ? ($data['stuck_on_intro_objectives']) : null;

    }

    public function exchangeArray(array $data)
    {

        if (!$this->id) $this->id = (isset($data['id']) && !empty($data['id'])) ? $data['id'] : null;

        $this->importFilename = (!empty($data['import_filename'])) ? $data['import_filename'] : $this->importFilename;
        $this->importedAt = (!empty($data['imported_at'])) ? new \DateTime($data['imported_at']) : $this->importedAt;
        $this->studentId = (!empty($data['student_id'])) ? $data['student_id'] : $this->studentId;

        $this->scd = (strlen($data['scd'])) ? ($data['scd']) : $this->scd;
        $this->firstName = (strlen($data['first_name'])) ? ($data['first_name']) : $this->firstName;
        $this->lastName = (strlen($data['last_name'])) ? ($data['last_name']) : $this->lastName;
        $this->schoolSessions = (strlen($data['school_sessions'])) ? ($data['school_sessions']) : $this->schoolSessions;
        $this->homeSessions = (strlen($data['home_sessions'])) ? ($data['home_sessions']) : $this->homeSessions;
        $this->syllabusProgress = (strlen($data['syllabus_progress'])) ? ($data['syllabus_progress']) : $this->syllabusProgress;
        $this->objectivesAttempted = (strlen($data['objectives_attempted'])) ? ($data['objectives_attempted']) : $this->objectivesAttempted;
        $this->standardsMastery = (strlen($data['standards_mastery'])) ? ($data['standards_mastery']) : $this->standardsMastery;
        $this->standardsAttempted = (strlen($data['standards_attempted'])) ? ($data['standards_attempted']) : $this->standardsAttempted;
        $this->currentObjective = (strlen($data['current_objective'])) ? ($data['current_objective']) : $this->currentObjective;
        $this->module = (strlen($data['module'])) ? ($data['module']) : $this->module;
        $this->game = (strlen($data['game'])) ? ($data['game']) : $this->game;
        $this->level = (strlen($data['level'])) ? ($data['level']) : $this->level;
        $this->totalAttempts = (strlen($data['total_attempts'])) ? ($data['total_attempts']) : $this->totalAttempts;
        $this->lastSession = (strlen($data['last_session'])) ? ($data['last_session']) : $this->lastSession;
        $this->alerts = (strlen($data['alerts'])) ? ($data['alerts']) : $this->alerts;
        $this->extraPlays = (strlen($data['extra_plays'])) ? ($data['extra_plays']) : $this->extraPlays;
        $this->lowPostQuizScore = (strlen($data['low_post_quiz_score'])) ? ($data['low_post_quiz_score']) : $this->lowPostQuizScore;
        $this->decreasingQuizScore = (strlen($data['decreasing_quiz_score'])) ? ($data['decreasing_quiz_score']) : $this->decreasingQuizScore;
        $this->lowStandardsMastery = (strlen($data['low_standards_mastery'])) ? ($data['low_standards_mastery']) : $this->lowStandardsMastery;
        $this->lowTimeOnTask = (strlen($data['low_time_on_task'])) ? ($data['low_time_on_task']) : $this->lowTimeOnTask;
        $this->highNumberOfTries = (strlen($data['high_number_of_tries'])) ? ($data['high_number_of_tries']) : $this->highNumberOfTries;
        $this->lowProgress = (strlen($data['low_progress'])) ? ($data['low_progress']) : $this->lowProgress;
        $this->lowAttendance = (strlen($data['low_attendance'])) ? ($data['low_attendance']) : $this->lowAttendance;
        $this->levelCancelling = (strlen($data['level_cancelling'])) ? ($data['level_cancelling']) : $this->levelCancelling;
        $this->unaddressedHandRaising = (strlen($data['unaddressed_hand_raising'])) ? ($data['unaddressed_hand_raising']) : $this->unaddressedHandRaising;
        $this->stuckOnIntroObjectives = (strlen($data['stuck_on_intro_objectives'])) ? ($data['stuck_on_intro_objectives']) : $this->stuckOnIntroObjectives;

        $this->exchangeDates($data);
    }

    public function toData() {

        $data = array(
            'import_filename' => $this->importFilename,
            'imported_at' => ($this->importedAt) ? ($this->importedAt->format('Y-m-d H:i:s')) : (null),
            'student_id' => $this->studentId,

            'scd' => $this->scd,
            'first_name' => $this->firstName,
            'last_name' => $this->lastName,
            'school_sessions' => $this->schoolSessions,
            'home_sessions' => $this->homeSessions,
            'syllabus_progress' => $this->syllabusProgress,
            'objectives_attempted' => $this->objectivesAttempted,
            'standards_mastery' => $this->standardsMastery,
            'standards_attempted' => $this->standardsAttempted,
            'current_objective' => $this->currentObjective,
            'module' => $this->module,
            'game' => $this->game,
            'level' => $this->level,
            'total_attempts' => $this->totalAttempts,
            'last_session' => $this->lastSession,
            'alerts' => $this->alerts,
            'extra_plays' => $this->extraPlays,
            'low_post_quiz_score' => $this->lowPostQuizScore,
            'decreasing_quiz_score' => $this->decreasingQuizScore,
            'low_standards_mastery' => $this->lowStandardsMastery,
            'low_time_on_task' => $this->lowTimeOnTask,
            'high_number_of_tries' => $this->highNumberOfTries,
            'low_progress' => $this->lowProgress,
            'low_attendance' => $this->lowAttendance,
            'level_cancelling' => $this->levelCancelling,
            'unaddressed_hand_raising' => $this->unaddressedHandRaising,
            'stuck_on_intro_objectives' => $this->stuckOnIntroObjectives,

        );

        return $data;

    }

    public function getArrayCopy() {
        return $this->toData();
    }

}