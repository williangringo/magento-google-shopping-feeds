<?php

$installer = $this;

$installer->run("CREATE TABLE IF NOT EXISTS `inchoo_gsfeeds` (
  `feed_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(70) CHARACTER SET latin1 NOT NULL,
  `link` varchar(100) CHARACTER SET latin1 NOT NULL,
  `last_update` datetime DEFAULT NULL,
  `title` varchar(100) CHARACTER SET latin1 DEFAULT NULL,
  `description` varchar(250) CHARACTER SET latin1 DEFAULT NULL,
  `categories` varchar(250) CHARACTER SET latin1 DEFAULT NULL,
  PRIMARY KEY (`feed_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
");

$installer->endSetup();