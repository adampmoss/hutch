<?php
/**
 * ProductAttributesexport.php
 * CommerceExtensions @ InterSEC Solutions LLC.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.commerceextensions.com/LICENSE-M1.txt
 *
 * @category   ProductAttributesexport
 * @package    Export Bulk Product Attributes
 * @copyright  Copyright (c) 2003-2009 CommerceExtensions @ InterSEC Solutions LLC. (http://www.commerceextensions.com)
 * @license    http://www.commerceextensions.com/LICENSE-M1.txt
 */ 


class CommerceExtensions_Productattributesimportexport_Model_Convert_Parser_ProductAttributesexport
    extends Mage_Eav_Model_Convert_Parser_Abstract
{
   
    public function parse()
    {
			return $this;
	}

    /**
     * Unparse (prepare data) loaded products
     *
     * @return Mage_Catalog_Model_Convert_Parser_Product
     */
	 
    public function unparse()
    {
             $storeID = $this->getVar('store');
             $EntityTypeId = $this->getVar('entitytypeid');
			 #$EntityTypeId = Mage::getModel('eav/entity')->setType('catalog_product')->getTypeId();
			 $recordlimit = $this->getVar('recordlimit');
			 $resource = Mage::getSingleton('core/resource');
			 $prefix = Mage::getConfig()->getNode('global/resources/db/table_prefix');
			 $read = $resource->getConnection('core_read');
			 $row = array();
			 
			 //get/set delimiters for use on export
			 if($this->getVar('attribute_options_delimiter') !="") {
				$attribute_options_delimiter = $this->getVar('attribute_options_delimiter');
			 } else {
				$attribute_options_delimiter = "|";
			 }
			 if($this->getVar('attribute_options_value_delimiter') !="") {
			 	$attribute_options_values_delimiter = $this->getVar('attribute_options_value_delimiter');
			 } else {
				$attribute_options_values_delimiter = ",";
			 }
			 
			 /* THIS IS 1.3.x and back SQL */
			 //SELECT m_eav_attribute.*, m_eav_attribute_set.*, m_eav_attribute_group.* FROM `m_eav_attribute` INNER JOIN m_eav_entity_attribute ON m_eav_entity_attribute.entity_type_id = m_eav_attribute.entity_type_id AND m_eav_entity_attribute.attribute_id = m_eav_attribute.attribute_id INNER JOIN m_eav_attribute_set ON m_eav_attribute_set.attribute_set_id = m_eav_entity_attribute.attribute_set_id INNER JOIN m_eav_attribute_group ON m_eav_attribute_group.attribute_group_id = m_eav_entity_attribute.attribute_group_id WHERE m_eav_attribute.entity_type_id = '10'
			 
			 /* THIS IS 1.4 SQL NOTE ADDITION OF catalog_eav_attribute */
			 //SELECT eav_attribute.*, eav_attribute_set.*, eav_attribute_group.*, catalog_eav_attribute.* FROM `eav_attribute` INNER JOIN eav_entity_attribute ON eav_entity_attribute.entity_type_id = eav_attribute.entity_type_id AND eav_entity_attribute.attribute_id = eav_attribute.attribute_id INNER JOIN catalog_eav_attribute ON catalog_eav_attribute.attribute_id = eav_attribute.attribute_id INNER JOIN eav_attribute_set ON eav_attribute_set.attribute_set_id = eav_entity_attribute.attribute_set_id INNER JOIN eav_attribute_group ON eav_attribute_group.attribute_group_id = eav_entity_attribute.attribute_group_id WHERE eav_attribute.entity_type_id = '10'
			  if($this->getVar('attribute_set_filter_list') !="") {
      		 	$attribute_filter_list = $this->getVar('attribute_set_filter_list');
				$finalsqlstringforattributecode = "";
				$attribute_code_data = explode(',', $attribute_filter_list);
				$counteritems=0;
				foreach($attribute_code_data as $single_attribute_code) {
					if($counteritems==0) {
						$finalsqlstringforattributecode .=  $prefix . "eav_attribute_set.attribute_set_name = '" . $single_attribute_code . "'";
					} else {
						$finalsqlstringforattributecode .=  " OR " . $prefix . "eav_attribute_set.attribute_set_name = '" . $single_attribute_code . "'";
					}
					$counteritems++;
				}
			 $select_qry = "SELECT ".$prefix."eav_attribute.*, ".$prefix."eav_attribute_set.*, ".$prefix."eav_attribute_group .*, ".$prefix."catalog_eav_attribute.* FROM `".$prefix."eav_attribute` INNER JOIN ".$prefix."eav_entity_attribute ON ".$prefix."eav_entity_attribute.entity_type_id = ".$prefix."eav_attribute.entity_type_id AND ".$prefix."eav_entity_attribute.attribute_id = ".$prefix."eav_attribute.attribute_id INNER JOIN ".$prefix."catalog_eav_attribute ON ".$prefix."catalog_eav_attribute.attribute_id = ".$prefix."eav_attribute.attribute_id INNER JOIN ".$prefix."eav_attribute_set ON ".$prefix."eav_attribute_set.attribute_set_id = ".$prefix."eav_entity_attribute.attribute_set_id INNER JOIN ".$prefix."eav_attribute_group ON ".$prefix."eav_attribute_group.attribute_group_id = ".$prefix."eav_entity_attribute.attribute_group_id WHERE ".$prefix."eav_attribute.entity_type_id = '".$EntityTypeId."' AND ".$finalsqlstringforattributecode." LIMIT ".$recordlimit."";
			 
			 } else {
			 
			 $select_qry = "SELECT ".$prefix."eav_attribute.*, ".$prefix."eav_attribute_set.*, ".$prefix."eav_attribute_group .*, ".$prefix."catalog_eav_attribute.* FROM `".$prefix."eav_attribute` INNER JOIN ".$prefix."eav_entity_attribute ON ".$prefix."eav_entity_attribute.entity_type_id = ".$prefix."eav_attribute.entity_type_id AND ".$prefix."eav_entity_attribute.attribute_id = ".$prefix."eav_attribute.attribute_id INNER JOIN ".$prefix."catalog_eav_attribute ON ".$prefix."catalog_eav_attribute.attribute_id = ".$prefix."eav_attribute.attribute_id INNER JOIN ".$prefix."eav_attribute_set ON ".$prefix."eav_attribute_set.attribute_set_id = ".$prefix."eav_entity_attribute.attribute_set_id INNER JOIN ".$prefix."eav_attribute_group ON ".$prefix."eav_attribute_group.attribute_group_id = ".$prefix."eav_entity_attribute.attribute_group_id WHERE ".$prefix."eav_attribute.entity_type_id = '".$EntityTypeId."' LIMIT ".$recordlimit."";
			 
			 }
			 #echo "SQL: " .$select_qry;
			 $rows = $read->fetchAll($select_qry);
					foreach($rows as $data)
					 { 
					 	 #print_r($data);
						 $row["EntityTypeId"] = $EntityTypeId;
						 $row["attribute_set"] = $data['attribute_set_name'];
						 $row["attribute_name"] = $data['attribute_code'];
						 $row["attribute_group_name"] = $data['attribute_group_name'];
						 $row["is_global"] = $data['is_global'];
						 $row["is_user_defined"] = $data['is_user_defined'];
						 $row["is_filterable"] = $data['is_filterable'];
						 $row["is_visible"] = $data['is_visible'];
						 $row["is_required"] = $data['is_required'];
						 $row["is_visible_on_front"] = $data['is_visible_on_front'];
						 $row["is_searchable"] = $data['is_searchable'];
						 $row["is_unique"] = $data['is_unique'];
						 $row["is_configurable"] = $data['is_configurable'];
						 
						 //latest additional fields (values = 0: NO / 1: YES)
						 $row["frontend_class"] = $data['frontend_class'];
						 $row["is_visible_in_advanced_search"] = $data['is_visible_in_advanced_search'];
						 $row["is_comparable"] = $data['is_comparable'];
						 $row["is_filterable_in_search"] = $data['is_filterable_in_search'];
						 $row["is_used_for_promo_rules"] = $data['is_used_for_promo_rules']; // 1.6.x + added
						 $row["position"] = $data['position'];
						 if(isset($data['is_html_allowed_on_front'])) {
						 $row["is_html_allowed_on_front"] = $data['is_html_allowed_on_front'];
						 }
						 if(isset($data['used_in_product_listing'])) {
						 $row["used_in_product_listing"] = $data['used_in_product_listing'];
						 }
						 if(isset($data['used_for_sort_by'])) {
						 $row["used_for_sort_by"] = $data['used_for_sort_by'];
						 }
										 
						 //frontend_input and backend_type #[useable types:]# decimal,int,select,text
						 $row["frontend_input"] = $data['frontend_input'];
						 $row["backend_type"] = $data['backend_type'];
						 if($data['frontend_label']!="") {	
								  $finalproductlabelattributes="";
						 		  $finalproductlabelattributes .=  "0:".$data['frontend_label'] . "|";
						 			$select_attribute_labels_qry = "SELECT store_id, value FROM ".$prefix."eav_attribute_label WHERE attribute_id = '".$data['attribute_id']."'";
						 			$attributelabelrows = $read->fetchAll($select_attribute_labels_qry);
									foreach($attributelabelrows as $attributelabeldata) { 
								 		 $finalproductlabelattributes .= $attributelabeldata["store_id"] . ":" . $attributelabeldata["value"] . "|";
									}		
						 		$row["frontend_label"] = substr_replace($finalproductlabelattributes,"",-1);		
									
						 } else {
						 		$row["frontend_label"] = $data['frontend_label'];
						 }
						 $row["default_value"] = $data['default_value'];
						 
						 //apply_to #[OPTIONAL usable types:]# simple,grouped,configurable,virtual,downloadable,bundle
						 $row["apply_to"] = $data['apply_to'];
							
						 //this will get all options for a attribute (dropdown/multi select/etc)
						 $finalproductattributes="";
						 if($storeID != "0") {
						 $select_attribute_options_qry = "SELECT ".$prefix."eav_attribute.*, ".$prefix."eav_attribute_option.sort_order, ".$prefix."eav_attribute_option_value.* FROM `".$prefix."eav_attribute` INNER JOIN ".$prefix."eav_attribute_option ON ".$prefix."eav_attribute_option.attribute_id = ".$prefix."eav_attribute.attribute_id INNER JOIN ".$prefix."eav_attribute_option_value ON ".$prefix."eav_attribute_option_value.option_id = ".$prefix."eav_attribute_option.option_id WHERE ".$prefix."eav_attribute.attribute_id = '".$data['attribute_id']."' AND ".$prefix."eav_attribute_option_value.store_id = '".$storeID."'";
						 } else {
						 $select_attribute_options_qry = "SELECT ".$prefix."eav_attribute.*, ".$prefix."eav_attribute_option.sort_order, ".$prefix."eav_attribute_option_value.* FROM `".$prefix."eav_attribute` INNER JOIN ".$prefix."eav_attribute_option ON ".$prefix."eav_attribute_option.attribute_id = ".$prefix."eav_attribute.attribute_id INNER JOIN ".$prefix."eav_attribute_option_value ON ".$prefix."eav_attribute_option_value.option_id = ".$prefix."eav_attribute_option.option_id WHERE ".$prefix."eav_attribute.attribute_id = '".$data['attribute_id']."'";
						 }
			 				/*
						 $attributeoptionrows = $read->fetchAll($select_attribute_options_qry);
								foreach($attributeoptionrows as $attributeoptiondata)
								 { 						
								 	$finalproductattributes .= $attributeoptiondata["store_id"] . ":" . $attributeoptiondata["value"] . "|";
						 		 }
						 $row["attribute_options"] = substr_replace($finalproductattributes,"",-1);
						 			
							*/
							$attributeoptionrows = $read->fetchAll($select_attribute_options_qry);
								foreach($attributeoptionrows as $attributeoptiondata)
								 {
								  if(!isset($temp) || $temp == $attributeoptiondata["option_id"]) {		 
								 	#if(isset($temp)) { echo "TEMP " . $temp; }
								 	#echo "OPTID " . $attributeoptiondata["option_id"];
										if($this->getVar('export_w_sort_order') != "true") {
								  			$finalproductattributes .= $attributeoptiondata["store_id"] . ":" . $attributeoptiondata["value"] . $attribute_options_values_delimiter;
										} else {
								  			$finalproductattributes .= $attributeoptiondata["store_id"] . ":" . $attributeoptiondata["value"] . ":" . $attributeoptiondata["sort_order"] . $attribute_options_values_delimiter;
										}	
									}	else { 
								 	#echo "TEMP1 " . $temp;
								 	#echo "OPTID1 " . $attributeoptiondata["option_id"];
											$finalproductattributes = substr_replace($finalproductattributes,"",-1);
											$finalproductattributes .= $attribute_options_delimiter;
											if($this->getVar('export_w_sort_order') != "true") {
								 				$finalproductattributes .= $attributeoptiondata["store_id"] . ":" . $attributeoptiondata["value"] . $attribute_options_values_delimiter;	
											} else {
								 				$finalproductattributes .= $attributeoptiondata["store_id"] . ":" . $attributeoptiondata["value"] . ":" . $attributeoptiondata["sort_order"] . $attribute_options_values_delimiter;	
											}
									}
								  $temp = $attributeoptiondata["option_id"];
						 		 }
								 $finalproductattributes = substr_replace($finalproductattributes,"",-1);
								 if($finalproductattributes !="") {
								 	$finalproductattributes .= $attribute_options_delimiter;
								 }
								 $finalproductattributes = ltrim($finalproductattributes, $attribute_options_delimiter);
								 $finalproductattributes = rtrim($finalproductattributes, $attribute_options_delimiter);
								 #echo "FULL: " . $finalproductattributes . "<br/>";
						 $row["attribute_options"] = $finalproductattributes;	
						 
            $batchExport = $this->getBatchExportModel()
                ->setId(null)
                ->setBatchId($this->getBatchModel()->getId())
                ->setBatchData($row)
                ->setStatus(1)
                ->save();
				 }
					
        return $this;
    }

    /**
     * Retrieve accessible external product attributes
     *
     * @return array
     */
    public function getExternalAttributes()
    {
        $entityTypeId = Mage::getSingleton('eav/config')->getEntityType('catalog_product')->getId();
        $productAttributes = Mage::getResourceModel('eav/entity_attribute_collection')
            ->setEntityTypeFilter($entityTypeId)
            ->load();

            #var_dump($this->_externalFields);

        $attributes = $this->_externalFields;

        foreach ($productAttributes as $attr) {
            $code = $attr->getAttributeCode();
            if (in_array($code, $this->_internalFields) || $attr->getFrontendInput() == 'hidden') {
                continue;
            }
            $attributes[$code] = $code;
        }

        foreach ($this->_inventoryFields as $field) {
            $attributes[$field] = $field;
        }

        return $attributes;
    }
}