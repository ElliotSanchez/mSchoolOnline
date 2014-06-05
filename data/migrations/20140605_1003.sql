ALTER TABLE `students` ADD `mname` VARCHAR(256)  NULL  DEFAULT NULL  AFTER `number`;

ALTER TABLE `students` AUTO_INCREMENT = 10000063;

UPDATE students SET id = id + 10000000;
UPDATE mclasses_students SET student_id = student_id + 10000000;
UPDATE progressions SET student_id = student_id + 10000000;
UPDATE sequences SET student_id = student_id + 10000000;
UPDATE student_logins SET student_id = student_id + 10000000;
UPDATE student_steps SET student_id = student_id + 10000000;


