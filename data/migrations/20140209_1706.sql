ALTER TABLE `students` ADD `dob` DATE  NULL  AFTER `last_name`;
ALTER TABLE `students` ADD `gender` ENUM('m','f')  NULL  DEFAULT NULL  AFTER `dob`;
ALTER TABLE `students` ADD `ethnicity` VARCHAR(256)  NULL  DEFAULT NULL  AFTER `gender`;
ALTER TABLE `students` ADD `iep` TINYINT(1)  NULL  DEFAULT '0'  AFTER `ethnicity`;
ALTER TABLE `students` ADD `grade_level` CHAR(2)  NULL  DEFAULT NULL  AFTER `iep`;


