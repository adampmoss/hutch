<?php
/**
 * Created by PhpStorm.
 * User: adammoss
 * Date: 07/10/15
 * Time: 22:30
 */ 
/* @var $installer Mage_Core_Model_Resource_Setup */
$installer = new Mage_Eav_Model_Entity_Setup('core_setup');

$installer->startSetup();

$installer->addAttribute('catalog_product', 'view_more_1', array(
    'group'         => 'General',
    'type'          => 'varchar',
    'input'         => 'text',
    'backend'       => '',
    'label'         => 'View More 1 (Category ID)',
    'visible'       => 1,
    'required'      => 0,
    'user_defined'  => 1,
    'searchable'    => 0,
    'filterable'    => 0,
    'comparable'    => 0,
    'visible_on_front'              => 1,
    'visible_in_advanced_search'    => 0,
    'is_html_allowed_on_front'      => 0,
    'global'        => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,
));

$installer->addAttribute('catalog_product', 'view_more_2', array(
    'group'         => 'General',
    'type'          => 'varchar',
    'input'         => 'text',
    'backend'       => '',
    'label'         => 'View More 2 (Category ID)',
    'visible'       => 1,
    'required'      => 0,
    'user_defined'  => 1,
    'searchable'    => 0,
    'filterable'    => 0,
    'comparable'    => 0,
    'visible_on_front'              => 1,
    'visible_in_advanced_search'    => 0,
    'is_html_allowed_on_front'      => 0,
    'global'        => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,
));

$installer->endSetup();