ALTER TABLE `accounts` ADD `is_default` TINYINT  NOT NULL  DEFAULT '0'  AFTER `subdomain`;
