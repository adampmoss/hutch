<?php
/**
 * ProductAttributesimport.php
 * CommerceExtensions @ InterSEC Solutions LLC.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.commerceextensions.com/LICENSE-M1.txt
 *
 * @category   ProductAttributesimport
 * @package    Import Bulk Product Attributes
 * @copyright  Copyright (c) 2003-2009 CommerceExtensions @ InterSEC Solutions LLC. (http://www.commerceextensions.com)
 * @license    http://www.commerceextensions.com/LICENSE-M1.txt
 */ 
 

class CommerceExtensions_Productattributesimportexport_Model_Convert_Adapter_ProductAttributesimport
extends Mage_Catalog_Model_Convert_Adapter_Product
{
	
	/**
	* Save Attributes (import)
	* 
	* @param array $importData 
	* @throws Mage_Core_Exception
	* @return bool 
	*/
	public function saveRow( array $importData )
	{
			/* @var $installer Mage_Customer_Model_Entity_Setup */
			//print_r($importData);
			 $EntityTypeId = $importData["EntityTypeId"];
			 $resource = Mage::getSingleton('core/resource');
			 $prefix = Mage::getConfig()->getNode('global/resources/db/table_prefix'); 
			 $write = $resource->getConnection('core_write');
			 $read = $resource->getConnection('core_read');
			 
			 $select_qry =$read->query("Select * from `".$prefix."eav_attribute_set` Where `attribute_set_name`=\"".$importData["attribute_set"]."\" and entity_type_id ='".$EntityTypeId."'");
			 $row = $select_qry->fetch();
			 $attributeSetId = $row['attribute_set_id'];
			 $attribute_set_exists = Mage::getModel('eav/entity_attribute_set')->load($attributeSetId); 
			 
			 //THIS MAKES SURE WE ONLY TRY TO INSERT AN UNQUIE ATTRIBUTE SET ONCE
			 if($importData["attribute_set"] != $attribute_set_exists->getAttributeSetName()) {
				 $modelSet = Mage::getModel('eav/entity_attribute_set')
										 ->setEntityTypeId($EntityTypeId);
				 $modelSet->setAttributeSetName($importData["attribute_set"]);
				 $modelSet->save();

                 if ($attributeSetId == "") {
                     $select_qry_set_id = $read->query("Select attribute_set_id from `" . $prefix . "eav_attribute_set` Where `attribute_set_name`='Default' and entity_type_id ='" . $EntityTypeId . "'");
                     $rowSetId = $select_qry_set_id->fetch();
                     $attributeSetId = $rowSetId['attribute_set_id'];
                 }
				 $modelSet->initFromSkeleton($attributeSetId)->save(); //FIX this is the attribute set ID we want to use aka Default.. may not be same in all cases
			 
				 $select_qry =$read->query("Select * from `".$prefix."eav_attribute_set` Where `attribute_set_name`=\"".$importData["attribute_set"]."\" and entity_type_id ='".$EntityTypeId."'");
				 $row = $select_qry->fetch();
				 $attributeSetId = $row['attribute_set_id'];
				 //$write_qry =$write->query("Insert into `".$prefix."eav_attribute_group` (attribute_set_id,attribute_group_name,sort_order,default_id) VALUES ('$attributeSetId','Imported Attributes','1','0')");
			 }
			
			 $attribute_code1 = Mage::getModel('eav/entity_attribute')->getIdByCode('catalog_product', $importData["attribute_name"]);
			 $attribute_exists = Mage::getModel('eav/entity_attribute')->load($attribute_code1); 
			 
			 #THIS DOES A CHECK TO SEE IF WE ARE SPECIFYING WHERE WE ARE PUTTING THE ATTRIBUTES INSIDE THE ATTRIBUTE SET. The else"by default" drops them into the general folder but including the column "attribute_group_name" in your CSV you can specify.
			 if($importData["attribute_group_name"] != "") {
				 $select_qry =$read->query("select attribute_group_id from `".$prefix."eav_attribute_group` where `attribute_set_id` = $attributeSetId and attribute_group_name = '".$importData["attribute_group_name"]."'");
				 $newrow = $select_qry->fetch();
				 $newGroupId = $newrow['attribute_group_id'];
				 if($newGroupId == "") {
					 $write_qry =$write->query("Insert into `".$prefix."eav_attribute_group` (attribute_set_id,attribute_group_name,sort_order,default_id) VALUES ('$attributeSetId','".$importData["attribute_group_name"]."','1','0')");
					 $select_qry =$read->query("select attribute_group_id from `".$prefix."eav_attribute_group` where `attribute_set_id` = $attributeSetId and attribute_group_name = '".$importData["attribute_group_name"]."'");
					 $newrow = $select_qry->fetch();
					 $newGroupId = $newrow['attribute_group_id'];
				 }
					
			 } else {
				 $select_qry =$read->query("select attribute_group_id from `".$prefix."eav_attribute_group` where `attribute_set_id` = $attributeSetId ");
				 $newrow = $select_qry->fetch();
				 $newGroupId = $newrow['attribute_group_id'];
			 }
			 #$model = Mage::getModel('catalog/entity_attribute'); // 1.5.x and back 1.6.x change below
			 $model = Mage::getModel('catalog/resource_eav_attribute');
			  if($attribute_exists->getId()=="") {
				 $data['attribute_code'] = $importData["attribute_name"];
				 $data['is_global'] = $importData["is_global"];
				 $data['is_user_defined'] = $importData["is_user_defined"];
				 $data['is_filterable'] = $importData["is_filterable"];
				 $data['is_visible'] = $importData["is_visible"];
				 $data['is_required'] = $importData["is_required"];
				 $data['is_visible_on_front'] = $importData["is_visible_on_front"];
				 $data['is_searchable'] = $importData["is_searchable"];
				 $data['is_unique'] = $importData["is_unique"];
				 $data['is_configurable'] = $importData["is_configurable"];
				 
				 //latest additional fields (values = 0: NO / 1: YES)
				 $data['frontend_class'] = $importData["frontend_class"];
				 $data['is_visible_in_advanced_search'] = $importData["is_visible_in_advanced_search"];
				 $data['is_comparable'] = $importData["is_comparable"];
				 $data['is_filterable_in_search'] = $importData["is_filterable_in_search"];
				 $data['is_used_for_promo_rules'] = $importData["is_used_for_promo_rules"];
				 #$data['is_used_for_price_rules'] = $importData["is_used_for_price_rules"];
				 $data['position'] = $importData["position"];
				 $data['is_html_allowed_on_front'] = $importData["is_html_allowed_on_front"];
				 $data['used_in_product_listing'] = $importData["used_in_product_listing"];
				 $data['used_for_sort_by'] = $importData["used_for_sort_by"];
				 				 
				 //frontend_input and backend_type #[useable types:]# decimal,int,select,text
				 $data['frontend_input'] = $importData["frontend_input"];
				 $data['backend_type'] = $importData["backend_type"];
				 if ($importData['frontend_input'] == "multiselect") {
				 $data['backend_model'] = 'eav/entity_attribute_backend_array';
				 }
					if($importData['frontend_label'] != "") {
					
						 #$data['frontend_label'] = $importData["frontend_label"];
						 $frontEndLabel=array();
						 $pipedelimiteddata = explode('|',$importData['frontend_label']);
						 foreach ($pipedelimiteddata as $datalabel) {
							 $pos = strpos($datalabel, ":");
							   if ($pos !== false) {
							 		$pipedelimiteddata1 = explode(':',$datalabel);
									//0 is the admin value, 1 is the "default store view" (frontend) value
									#$frontEndLabel=array('0'=>'Media Format', '1'=>'Media Format');
									$frontEndLabel[$pipedelimiteddata1[0]] = $pipedelimiteddata1[1];
								} else {
									$frontEndLabel[0] = $datalabel;
								}
						 }
						 $model->setFrontendLabel($frontEndLabel);	
						
					} 
				 if($importData['attribute_options'] != "") {
					$optionData=array();
					$optionDataValues=array();
					$attributevaluecounter=0;
					$data['option'] = array('value' => array());
					if($this->getBatchParams('attribute_options_delimiter') != "") {
					 	$attribute_options_delimiter = $this->getBatchParams('attribute_options_delimiter');
					} else {
					 	$attribute_options_delimiter = "|";
					}
					if($this->getBatchParams('attribute_options_value_delimiter') != "") {
					 	$attribute_options_values_delimiter = $this->getBatchParams('attribute_options_value_delimiter');
					} else {
					 	$attribute_options_values_delimiter = ",";
					}
					$pipedelimiteddata = explode($attribute_options_delimiter,$importData['attribute_options']);
					 foreach ($pipedelimiteddata as $attribute_options_data) {
					 
						 $pipedelimiteddatabycomma = explode($attribute_options_values_delimiter,$attribute_options_data);
						  foreach ($pipedelimiteddatabycomma as $options_data) {
						  		// change $options_data to $pre_options_data
								/*
								$checkactualattributeoptiondata = explode(':',$pre_options_data);
								if(!isset($checkactualattributeoptiondata[1])) {
									$options_data = "0:".$pre_options_data;
								} else {
									$options_data = $pre_options_data;
								}
								*/
								//this could be a trick for when you are only loading all default values.. no need to set 0:value,0:next could do value,next basically
								$actualattributeoptiondata = explode(':',$options_data);
								#$this->addAttributeValue($importData["attribute_name"], $data);
								//0 is the admin value, 1 is the "default store view" (frontend) value
								#$data['option']['value']['option_'.$attributevaluecounter.''][$actualattributeoptiondata[0]] = $actualattributeoptiondata[1];
								#$data['option']['value'] = array("option_".$attributevaluecounter."" => array($actualattributeoptiondata[0] => $actualattributeoptiondata[1]));
							  #$data['option']['value']['option_'.$attributevaluecounter.''] = array(0 => $actualattributeoptiondata[1], 2 => "test2");
								if(isset($actualattributeoptiondata[1])) {
									$optionDataValues[$actualattributeoptiondata[0]] = $actualattributeoptiondata[1];
									#$optionDataValues[0] = $actualattributeoptiondata[1];
									#echo "LABEL: " . $actualattributeoptiondata[1];
									$data['option']['value']['option_'.$attributevaluecounter.''] = $optionDataValues;
									if($this->getBatchParams('import_w_sort_order') == "true") {
										if(isset($actualattributeoptiondata[2])) {
											$data['option']['order']['option_'.$attributevaluecounter.''] = $actualattributeoptiondata[2];
										}
									}
									//$optionDataValues[0] = "";
									/* $optionData = array('value' =>
															array('option_0'=>array('0'=>'dvd','1'=>'dvd'),
																		'option_1'=>array('0'=>'vhs','1'=>'vhs'),     
																		'option_2'=>array('0'=>'blue-ray','1'=>'blue-ray')     
															)
														,
																'order' =>
															array('option_0'=>1,
																	'option_1'=>2,
																	'option_2'=>3)
													 ); */
								}
							}
						 #$optionData['order']['option_'.$attributevaluecounter.''] = $attributevaluecounter+1;
						 $attributevaluecounter+=1;
					 }
					  #print_r($optionData);
						
						#print_r($data);
						$model->addData($data);
						#$model->setOption($optionData);
						#$model->setOption($optionData);
				 }
				 $data['default_value'] = $importData["default_value"];
				 
				 //apply_to #[OPTIONAL usable types:]# simple,grouped,configurable,virtual,downloadable,bundle
				 $data['apply_to'] = $importData["apply_to"];
				 
				 //FINALLY SAVE THE ROW TO DATABASE
				 $model->addData($data);
				 $model->setIsUserDefined($importData["is_user_defined"]);
				 $model->setAttributeGroupId($newGroupId);
				 $model->setAttributeSetId($attributeSetId);
				 #$model->setEntityTypeId(Mage::getModel('eav/entity')->setType('catalog_product')->getTypeId());
				 $model->setEntityTypeId($EntityTypeId); 
				 $model->save();
				} #ends check to see if attribute already exists
				else {
				// CODE THAT UPDATES
				
				 if($importData["frontend_input"] == "price" || $importData["frontend_input"] == "multiselect" || $importData["frontend_input"] == "select") {
					 $data_update['is_filterable'] = $importData["is_filterable"];
				 	 $data_update['is_filterable_in_search'] = $importData["is_filterable_in_search"];
				 }
				 
				 $data_update['is_global'] = $importData["is_global"];
				 $data_update['is_visible'] = $importData["is_visible"];
				 $data_update['is_visible_on_front'] = $importData["is_visible_on_front"];
				 $data_update['is_unique'] = $importData["is_unique"];
				 $data_update['is_searchable'] = $importData["is_searchable"];
				 $data_update['is_required'] = $importData["is_required"];
				 $data_update['is_visible_in_advanced_search'] = $importData["is_visible_in_advanced_search"];
				 $data_update['is_comparable'] = $importData["is_comparable"];
				 $data_update['is_used_for_promo_rules'] = $importData["is_used_for_promo_rules"];
				 #$data_update['is_used_for_price_rules'] = $importData["is_used_for_price_rules"];
				 $data_update['position'] = $importData["position"];
				 $data_update['is_html_allowed_on_front'] = $importData["is_html_allowed_on_front"];
				 $data_update['used_in_product_listing'] = $importData["used_in_product_listing"];
				 $data_update['used_for_sort_by'] = $importData["used_for_sort_by"];
				 #$data_update['default_value'] = $importData["default_value"];
				 
				 $attribute_exists->addData($data_update);
				 $attribute_exists->setIsUserDefined($importData["is_user_defined"]);
				 $attribute_exists->setAttributeGroupId($newGroupId);
				 $attribute_exists->setAttributeSetId($attributeSetId);
				 $attribute_exists->setEntityTypeId($EntityTypeId); 
				 $attribute_exists->save(); 
				 #$attribute_update = Mage::getModel('catalog/resource_eav_attribute')->load($attribute_exists->getId());
				 
				 
				 $result = array();
				 		
				 	   if($importData['frontend_label'] != "") {
					
						 #$data['frontend_label'] = $importData["frontend_label"];
						 $frontEndLabel=array();
						 $pipedelimiteddata = explode('|',$importData['frontend_label']);
						 foreach ($pipedelimiteddata as $datalabel) {
							 $pos = strpos($datalabel, ":");
							   if ($pos !== false) {
							 		$pipedelimiteddata1 = explode(':',$datalabel);
									//0 is the admin value, 1 is the "default store view" (frontend) value
									#$frontEndLabel=array('0'=>'Media Format', '1'=>'Media Format');
									$frontEndLabel[$pipedelimiteddata1[0]] = $pipedelimiteddata1[1];
								} else {
									$frontEndLabel[0] = $datalabel;
								}
						 }
						$attribute_model        = Mage::getModel('eav/entity_attribute');
						$attribute_code         = $attribute_model->getIdByCode('catalog_product', $importData["attribute_name"]);
						$attributelabelmodel    = $attribute_model->load($attribute_code);
						$attributelabelmodel->setFrontendLabel($frontEndLabel);
						$attributelabelmodel->save();	
						
					  } 
					  
						if($this->getBatchParams('attribute_options_delimiter') != "") {
							$attribute_options_delimiter = $this->getBatchParams('attribute_options_delimiter');
						} else {
							$attribute_options_delimiter = "|";
						}
						if($this->getBatchParams('attribute_options_value_delimiter') != "") {
							$attribute_options_values_delimiter = $this->getBatchParams('attribute_options_value_delimiter');
						} else {
							$attribute_options_values_delimiter = ",";
						}
						
					  if($importData['attribute_options'] != "") {
						   $attributeupdatevaluecounter=0;
						   $pipedelimiteddata = explode($attribute_options_delimiter,$importData['attribute_options']);
							 foreach ($pipedelimiteddata as $options_data_update) {
									  $thisotherdatacommadelimited = explode($attribute_options_values_delimiter,$options_data_update);
										foreach ($thisotherdatacommadelimited as $options_data1) {
											$actualattributeoptiondata_update = explode(':',$options_data1);
											#$this->addAttributeValue($importData["attribute_name"], $actualattributeoptiondata_update[1], $actualattributeoptiondata_update[0]);
											$attribute_model        = Mage::getModel('eav/entity_attribute');
											$attribute_code         = $attribute_model->getIdByCode('catalog_product', $importData["attribute_name"]);
											$attribute              = $attribute_model->load($attribute_code);
											
											if(!$this->attributeValueExists($importData["attribute_name"], $actualattributeoptiondata_update[1], $actualattributeoptiondata_update[0]))
        									{
									
											if($actualattributeoptiondata_update[1] !="") {
											
												$result['value']['option_'.$attributeupdatevaluecounter.''][$actualattributeoptiondata_update[0]] = $actualattributeoptiondata_update[1];		
												
												if($this->getBatchParams('import_w_sort_order') == "true") {
													if(isset($actualattributeoptiondata_update[2])) {
														$result['order']['option_'.$attributeupdatevaluecounter.''] = $actualattributeoptiondata_update[2];
													}
												}
											}
											}
										}
						 		$attributeupdatevaluecounter+=1;
							 }
							 
							#$result = array('value' => $value);
							#print_r($result);
							if(!empty($result)) {
								$attribute->setData('option',$result);
								$attribute->save();
							}
					 } 
				
				//OLD CODE 
				/*
				 $attribute_exists->setIsUserDefined($importData["is_user_defined"]);
				 $attribute_exists->setAttributeGroupId($newGroupId);
				 $attribute_exists->setAttributeSetId($attributeSetId);
				 $attribute_exists->setEntityTypeId($EntityTypeId); 
				 $attribute_exists->save(); 
				 
				 
					  if($importData['attribute_options'] != "") {
						   $pipedelimiteddata = explode('|',$importData['attribute_options']);
							 foreach ($pipedelimiteddata as $options_data_update) {
										$actualattributeoptiondata_update = explode(':',$options_data_update);
										$this->addAttributeValue($importData["attribute_name"], $actualattributeoptiondata_update[1]);
									
							 }
					 } 
				*/
				//even older code
				/*
			 if($importData['attribute_options'] != "") {
				 $pipedelimiteddata = explode('|',$importData['attribute_options']);
				 foreach ($pipedelimiteddata as $data) {
					 $this->addAttributeValue($importData["attribute_name"], $data);
				 }
			 } 
			 */
				}
				
			 
		return true;
	} 
	
	protected function userCSVDataAsArray( $data )
	{
		return explode( ',', str_replace( " ", "", $data ) );
	} 
	public function attributeValueExists($arg_attribute, $arg_value, $arg_store_id)
    {
        $attribute_model        = Mage::getModel('eav/entity_attribute');
        $attribute_code         = $attribute_model->getIdByCode('catalog_product', $arg_attribute);
        $attribute              = $attribute_model->load($attribute_code);
		
		// THIS WILL DELETE A EXISTING ATTRIBUTE OPTION AND REIMPORT A NEW. THIS COULD BE ISSUE FOR PRODUCTS WITH EXISTING ATTRIBUTE OPTIONS ASSIGNED THEM AS DELETING AND REIMPORTING NEW CHANGES ID.
		
		if($arg_store_id ==0) {
				
				$valuesCollection = Mage::getResourceModel('eav/entity_attribute_option_collection')
								->setAttributeFilter($attribute->getId())
								->setStoreFilter($arg_store_id, false);
				foreach($valuesCollection as $option)
				{
					if ($option['value'] != "") { $option->delete(); }
				}
				
			return false;
		}
		
		$valuesCollection = Mage::getResourceModel('eav/entity_attribute_option_collection')
							->setAttributeFilter($attribute->getId())
							->setStoreFilter($arg_store_id, true);
		foreach($valuesCollection as $value_option)
        {
            if ($value_option['value'] == htmlspecialchars($arg_value))
            {	
			   #$idforreturn = $value_option->getId();
               #return $idforreturn;
               return $value_option['value'];
               #return true;
            } 
		}
		
        return false;
    }
	protected function addAttributeValue($arg_attribute, $arg_value)
  {
        $attribute_model        = Mage::getModel('eav/entity_attribute');
        $attribute_options_model= Mage::getModel('eav/entity_attribute_source_table') ;

        $attribute_code         = $attribute_model->getIdByCode('catalog_product', $arg_attribute);
        $attribute              = $attribute_model->load($attribute_code);
        
        $attribute_table        = $attribute_options_model->setAttribute($attribute);
        $options                = $attribute_options_model->getAllOptions(false);
        
        if(!$this->attributeValueExists($arg_attribute, $arg_value))
        {
				$value['option'] = array($arg_value,$arg_value);
				$result = array('value' => $value);
				$attribute->setData('option',$result);
				$attribute->save();
        }
        
        foreach($options as $option)
        {
            if ($option['label'] == $arg_value)
            {
                return $option['value'];
            }
        }
     return true;
  }
			
	protected function _removeFile( $file )
	{
		if ( file_exists( $file ) ) {
			if ( unlink( $file ) ) {
				return true;
			} 
		} 
		return false;
	} 
}