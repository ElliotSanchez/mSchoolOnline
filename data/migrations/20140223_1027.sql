CREATE TABLE `pathways` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `student_id` int(11) NOT NULL,
  `resource_id` int(11) NOT NULL,
  `pathway_date` date DEFAULT NULL,
  `step` tinyint(4) NOT NULL,
  `timer` tinyint(4) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL,
  `upload_set_id` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;