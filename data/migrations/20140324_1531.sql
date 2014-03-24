CREATE TABLE `pathway_plans` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `pathway_id` int(11) NOT NULL,
  `plan_id` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `pathway_id` (`pathway_id`),
  KEY `plan_id` (`plan_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;