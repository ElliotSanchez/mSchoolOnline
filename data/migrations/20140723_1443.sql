CREATE VIEW dreambox_usage_lp AS
SELECT student_id, MAX(unique_lessons_completed * 5) AS learning_points
FROM dreambox_usage
GROUP BY student_id
ORDER BY student_id;

CREATE VIEW stmath_progress_lp AS
SELECT student_id, MAX(CAST(syllabus_progress AS UNSIGNED INT) * 8) AS learning_points
FROM stmath_progress
GROUP BY student_id
ORDER BY student_id ASC;

CREATE VIEW ttm_overview_lp AS
SELECT student_id, MAX(CAST(total_lessons_passed AS UNSIGNED INT) * 15) AS learning_points
FROM ttm_overview
GROUP BY student_id
ORDER BY student_id ASC;