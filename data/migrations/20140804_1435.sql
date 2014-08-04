ALTER TABLE `coach_signups` ADD `confirmation_key` VARCHAR(256)  NULL  DEFAULT NULL  AFTER `role`;

ALTER TABLE `coach_signups` ADD `is_confirmed` TINYINT(1)  NOT NULL  DEFAULT '0'  AFTER `confirmation_key`;

ALTER TABLE `coach_signups` CHANGE `role` `role` VARCHAR(256)  NULL  DEFAULT NULL;
