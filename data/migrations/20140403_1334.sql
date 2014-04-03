ALTER TABLE `progressions` ADD `student_id` INT  NULL  DEFAULT NULL  AFTER `id`;
ALTER TABLE `progressions` ADD INDEX (`student_id`);

ALTER TABLE `progressions` CHANGE `created_at` `created_at` DATETIME  NOT NULL;
ALTER TABLE `progressions` CHANGE `updated_at` `updated_at` DATETIME  NULL;
