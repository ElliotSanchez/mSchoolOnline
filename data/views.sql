-- TIME ON MATH
CREATE VIEW `digitwhiz_time_total_seconds`AS
SELECT `digitwhiz_time`.`student_id` AS `student_id`, sum(time_to_sec(cast(concat('00:',`digitwhiz_time`.`dwtime`) as time))) AS `digitwhiz_time`
FROM `digitwhiz_time` GROUP BY `digitwhiz_time`.`student_id` ORDER BY `digitwhiz_time`.`student_id`;

CREATE VIEW `dreambox_usage_total_seconds` AS
SELECT `dreambox_usage`.`student_id` AS `student_id`, max(time_to_sec(cast(concat(`dreambox_usage`.`time_on_task`,':00') as time))) AS `total_seconds`
FROM `dreambox_usage` GROUP BY `dreambox_usage`.`student_id` ORDER BY `dreambox_usage`.`student_id`;

CREATE VIEW `stmath_usage_total_seconds` AS
SELECT `stmath_usage`.`student_id` AS `student_id`, max(trim(replace(if((`stmath_usage`.`total_time` = '--'),0,`stmath_usage`.`total_time`),'min.','')) * 60) AS `total_seconds`
FROM `stmath_usage` GROUP BY `stmath_usage`.`student_id` ORDER BY `stmath_usage`.`student_id`;

CREATE VIEW `ttm_overview_total_seconds`AS
SELECT `ttm_overview`.`student_id` AS `student_id`, max(time_to_sec(cast(concat(`ttm_overview`.`total_math_time`,':00') as time))) AS `total_seconds`
FROM `ttm_overview` GROUP BY `ttm_overview`.`student_id` ORDER BY `ttm_overview`.`student_id`;

-- LEARNING POINTS
CREATE VIEW dreambox_usage_lp AS
SELECT student_id, MAX(unique_lessons_completed) AS learning_points
FROM dreambox_usage
GROUP BY student_id
ORDER BY student_id ASC;

CREATE VIEW stmath_progress_lp AS
SELECT student_id, MAX(CAST(syllabus_progress AS UNSIGNED INT)) * 8 AS learning_points
FROM stmath_progress
GROUP BY student_id
ORDER BY student_id ASC;

CREATE VIEW ttm_overview_lp AS
SELECT student_id, MAX(CAST(total_lessons_passed AS UNSIGNED INT)) * 15 AS learning_points
FROM ttm_overview
GROUP BY student_id
ORDER BY student_id ASC;