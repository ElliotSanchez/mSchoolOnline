CREATE TABLE `digitwhiz_time` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `import_filename` varchar(256) DEFAULT NULL,
  `imported_at` datetime DEFAULT NULL,
  `student_id` int(11) DEFAULT NULL,

  `scd` varchar(256) DEFAULT NULL,
  `first_name` varchar(256) DEFAULT NULL,
  `last_name` varchar(256) DEFAULT NULL,
  `avg_time_week` varchar(256) DEFAULT NULL,
  `avg_progress_week` varchar(256) DEFAULT NULL,
  `total_time` varchar(256) DEFAULT NULL,
  `syllabus_progress` varchar(256) DEFAULT NULL,
  `first_login` varchar(256) DEFAULT NULL,

  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `student_id` (`student_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;