CREATE TABLE `mclasses_students` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `mclass_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `mclass_id` (`mclass_id`),
  KEY `student_id` (`student_id`),
  KEY `mclass_student` (`mclass_id`,`student_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `mclasses_teachers` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `mclass_id` int(11) NOT NULL,
  `teacher_id` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `mclass_id` (`mclass_id`),
  KEY `teacher_id` (`teacher_id`),
  KEY `mclass_teacher` (`mclass_id`,`teacher_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;