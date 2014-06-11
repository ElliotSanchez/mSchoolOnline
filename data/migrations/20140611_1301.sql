ALTER TABLE `progressions` ADD `is_active` TINYINT  NOT NULL  DEFAULT '1'  AFTER `completed_at`;

ALTER TABLE `progressions` ADD `skipped_at` DATETIME  NULL  DEFAULT NULL  AFTER `completed_at`;
ALTER TABLE `student_steps` ADD `skipped_at` DATETIME  NULL  DEFAULT NULL  AFTER `completed_at`;


