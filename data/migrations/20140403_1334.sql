ALTER TABLE `progressions` ADD `student_id` INT  NULL  DEFAULT NULL  AFTER `id`;
ALTER TABLE `progressions` ADD INDEX (`student_id`);

ALTER TABLE `progressions` CHANGE `created_at` `created_at` DATETIME  NOT NULL;
ALTER TABLE `progressions` CHANGE `updated_at` `updated_at` DATETIME  NULL;

ALTER TABLE `student_steps` ADD `is_complete` INT  NOT NULL  DEFAULT '0'  AFTER `step_order`;

ALTER TABLE `sequences` ADD `completed_at` DATETIME  NULL  AFTER `is_complete`;
ALTER TABLE `progressions` ADD `completed_at` DATETIME  NULL  AFTER `is_complete`;
ALTER TABLE `student_steps` ADD `completed_at` DATETIME  NULL  AFTER `is_complete`;