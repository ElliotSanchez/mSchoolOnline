CREATE TABLE `progressions` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `sequence_id` int(11) NOT NULL,
  `plan_id` int(11) NOT NULL,
  `activity_date` date DEFAULT NULL,
  `is_complete` tinyint(4) NOT NULL DEFAULT '0',
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


ALTER TABLE `student_steps` ADD `plan_group` TINYINT  NULL  DEFAULT NULL  AFTER `step_id`;


ALTER TABLE `steps` CHANGE `timer` `timer` SMALLINT(6)  NULL;
