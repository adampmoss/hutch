<?php

$installer = new Mage_Eav_Model_Entity_Setup('core_setup');

$installer->startSetup();
$entityTypeId = 'catalog_category';

$installer->addAttribute($entityTypeId, 'range_image_1', array(
    'group'         => 'Range Category',
    'input'         => 'image',
    'type'          => 'varchar',
    'backend' => 'catalog/category_attribute_backend_image',
    'label'         => 'Range Image 1',
    'visible'       => 1,
    'required'      => 0,
    'user_defined' => 1,
    'global'        => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE
));

$installer->addAttribute($entityTypeId, 'range_image_2', array(
    'group'         => 'Range Category',
    'input'         => 'image',
    'type'          => 'varchar',
    'backend' => 'catalog/category_attribute_backend_image',
    'label'         => 'Range Image 2',
    'visible'       => 1,
    'required'      => 0,
    'user_defined' => 1,
    'global'        => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE
));

$installer->addAttribute($entityTypeId, 'range_image_3', array(
    'group'         => 'Range Category',
    'input'         => 'image',
    'type'          => 'varchar',
    'backend' => 'catalog/category_attribute_backend_image',
    'label'         => 'Range Image 3',
    'visible'       => 1,
    'required'      => 0,
    'user_defined' => 1,
    'global'        => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE
));

$installer->addAttribute($entityTypeId, 'range_image_4', array(
    'group'         => 'Range Category',
    'input'         => 'image',
    'type'          => 'varchar',
    'backend' => 'catalog/category_attribute_backend_image',
    'label'         => 'Range Image 4',
    'visible'       => 1,
    'required'      => 0,
    'user_defined' => 1,
    'global'        => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE
));

$installer->addAttribute($entityTypeId, 'range_image_5', array(
    'group'         => 'Range Category',
    'input'         => 'image',
    'type'          => 'varchar',
    'backend' => 'catalog/category_attribute_backend_image',
    'label'         => 'Range Image 5',
    'visible'       => 1,
    'required'      => 0,
    'user_defined' => 1,
    'global'        => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE
));

$installer->addAttribute($entityTypeId, 'range_image_6', array(
    'group'         => 'Range Category',
    'input'         => 'image',
    'type'          => 'varchar',
    'backend' => 'catalog/category_attribute_backend_image',
    'label'         => 'Range Image 6',
    'visible'       => 1,
    'required'      => 0,
    'user_defined' => 1,
    'global'        => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE
));


$installer->addAttribute($entityTypeId, 'featured_range_1', array(
    'group'         => 'Room Category',
    'input'         => 'text',
    'type'          => 'varchar',
    'label'         => 'Featured Range 1',
    'visible'       => 1,
    'required'      => 0,
    'user_defined' => 1,
    'global'        => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE
));

$installer->addAttribute($entityTypeId, 'featured_range_2', array(
    'group'         => 'Room Category',
    'input'         => 'text',
    'type'          => 'varchar',
    'label'         => 'Featured Range 2',
    'visible'       => 1,
    'required'      => 0,
    'user_defined' => 1,
    'global'        => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE
));

$installer->addAttribute($entityTypeId, 'featured_range_3', array(
    'group'         => 'Room Category',
    'input'         => 'text',
    'type'          => 'varchar',
    'label'         => 'Featured Range 3',
    'visible'       => 1,
    'required'      => 0,
    'user_defined' => 1,
    'global'        => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE
));

$installer->addAttribute($entityTypeId, 'featured_range_4', array(
    'group'         => 'Room Category',
    'input'         => 'text',
    'type'          => 'varchar',
    'label'         => 'Featured Range 4',
    'visible'       => 1,
    'required'      => 0,
    'user_defined' => 1,
    'global'        => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE
));

$installer->addAttribute($entityTypeId, 'featured_range_5', array(
    'group'         => 'Room Category',
    'input'         => 'text',
    'type'          => 'varchar',
    'label'         => 'Featured Range 5',
    'visible'       => 1,
    'required'      => 0,
    'user_defined' => 1,
    'global'        => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE
));

$installer->addAttribute($entityTypeId, 'featured_range_6', array(
    'group'         => 'Room Category',
    'input'         => 'text',
    'type'          => 'varchar',
    'label'         => 'Featured Range 6',
    'visible'       => 1,
    'required'      => 0,
    'user_defined' => 1,
    'global'        => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE
));

$installer->endSetup();