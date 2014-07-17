CREATE TABLE `grade_levels` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(256) DEFAULT NULL,
  `sort_order` tinyint(3) unsigned NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `grade_levels` (`id`, `name`, `sort_order`, `created_at`, `updated_at`) VALUES (NULL, 'K', '1', NOW(), NULL);
INSERT INTO `grade_levels` (`id`, `name`, `sort_order`, `created_at`, `updated_at`) VALUES (NULL, '1st', '2', NOW(), NULL);
INSERT INTO `grade_levels` (`id`, `name`, `sort_order`, `created_at`, `updated_at`) VALUES (NULL, '2nd', '3', NOW(), NULL);
INSERT INTO `grade_levels` (`id`, `name`, `sort_order`, `created_at`, `updated_at`) VALUES (NULL, '3rd', '4', NOW(), NULL);
INSERT INTO `grade_levels` (`id`, `name`, `sort_order`, `created_at`, `updated_at`) VALUES (NULL, '4th', '5', NOW(), NULL);
INSERT INTO `grade_levels` (`id`, `name`, `sort_order`, `created_at`, `updated_at`) VALUES (NULL, '5th', '6', NOW(), NULL);
INSERT INTO `grade_levels` (`id`, `name`, `sort_order`, `created_at`, `updated_at`) VALUES (NULL, '6th', '7', NOW(), NULL);
INSERT INTO `grade_levels` (`id`, `name`, `sort_order`, `created_at`, `updated_at`) VALUES (NULL, '7th', '8', NOW(), NULL);
INSERT INTO `grade_levels` (`id`, `name`, `sort_order`, `created_at`, `updated_at`) VALUES (NULL, '8th', '9', NOW(), NULL);
INSERT INTO `grade_levels` (`id`, `name`, `sort_order`, `created_at`, `updated_at`) VALUES (NULL, '9th', '10', NOW(), NULL);
INSERT INTO `grade_levels` (`id`, `name`, `sort_order`, `created_at`, `updated_at`) VALUES (NULL, '10th', '11', NOW(), NULL);
INSERT INTO `grade_levels` (`id`, `name`, `sort_order`, `created_at`, `updated_at`) VALUES (NULL, '11th', '12', NOW(), NULL);
INSERT INTO `grade_levels` (`id`, `name`, `sort_order`, `created_at`, `updated_at`) VALUES (NULL, '12th', '13', NOW(), NULL);

ALTER TABLE `students` CHANGE `grade_level` `grade_level_id` INT(11)  NULL  DEFAULT NULL;

UPDATE students SET grade_level = grade_level + 1;