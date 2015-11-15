<?php

$installer = new Mage_Eav_Model_Entity_Setup('core_setup');

$installer->startSetup();
$entityTypeId = 'catalog_category';

$installer->addAttribute($entityTypeId, 'description_2', array(
'group'         => 'General Information',
'input'         => 'textarea',
'type'          => 'text',
'label'         => 'Description 2',
'visible'       => 1,
'required'      => 0,
'user_defined' => 1,
'global'        => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE
));

$installer->endSetup();