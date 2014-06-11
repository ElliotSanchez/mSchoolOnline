ALTER TABLE `progressions` ADD `is_active` TINYINT  NOT NULL  DEFAULT '1'  AFTER `completed_at`;
