CREATE TABLE `stmath_student` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `import_filename` varchar(256) DEFAULT NULL,
  `imported_at` datetime DEFAULT NULL,
  `student_id` int(11) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `student_id` (`student_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;