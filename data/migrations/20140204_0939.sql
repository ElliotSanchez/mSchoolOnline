CREATE TABLE `schools` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `account_id` int(11) DEFAULT NULL,
  `name` varchar(256) DEFAULT '',
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `account` (`account_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;