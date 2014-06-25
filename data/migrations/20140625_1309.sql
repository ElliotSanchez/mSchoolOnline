CREATE TABLE `iready_data` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `iready_id` int(11) NOT NULL,
  `column` varchar(256) DEFAULT NULL,
  `value` varchar(256) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `dreambox_usage` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `import_filename` varchar(256) DEFAULT NULL,
  `imported_at` datetime DEFAULT NULL,
  `student_id` int(11) DEFAULT NULL,
  `last_name` varchar(256) DEFAULT '',
  `first_name` varchar(256) DEFAULT NULL,
  `student_grade` varchar(256) DEFAULT NULL,
  `teacher_emails` varchar(256) DEFAULT NULL,
  `class_name` varchar(256) DEFAULT NULL,
  `school_name` varchar(256) DEFAULT NULL,
  `intervention` varchar(256) DEFAULT NULL,
  `sessions` varchar(256) DEFAULT NULL,
  `time_on_task` varchar(256) DEFAULT NULL,
  `lessons_completed` varchar(256) DEFAULT NULL,
  `unique_lessons_completed` varchar(256) DEFAULT NULL,
  `units_completed` varchar(256) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `student_id` (`student_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;