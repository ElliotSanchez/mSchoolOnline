CREATE TABLE `dreambox_standards_data` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `dreambox_standards_id` int(11) NOT NULL,
  `column` varchar(256) DEFAULT NULL,
  `value` varchar(256) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;