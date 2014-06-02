ALTER TABLE `sequences` ADD `moved_on` TINYINT  NOT NULL  DEFAULT '0'  AFTER `completed_at`;
