<?php

$installer = $this;

$installer->startSetup();

$setup = new Mage_Eav_Model_Entity_Setup('core_setup');
// below code will add text attribute

$setup->addAttribute('catalog_category', 'google_shopping_mapping', array(
    'group' => 'General',
    'input' => 'text',
    'type' => 'text',
    'label' => 'Google shopping category mapping',
    'backend' => '',
    'visible' => true,
    'required' => false,
    'visible_on_front' => true,
    'global' => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
));

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