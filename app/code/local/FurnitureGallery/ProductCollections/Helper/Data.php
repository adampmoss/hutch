<?php
/**
 * Created by PhpStorm.
 * User: adammoss
 * Date: 20/09/15
 * Time: 13:06
 */ 
class FurnitureGallery_ProductCollections_Helper_Data extends Mage_Core_Helper_Abstract {

    public function getSaleItems()
    {
        $categoryId = 19;

        $category = Mage::getModel('catalog/category')->load($categoryId);
        $collection = Mage::getModel('catalog/product')->getCollection()
            ->addAttributeToSelect('small_image')
            ->addAttributeToSelect('price')
            ->addAttributeToSelect('msrp')
            ->addAttributeToSelect('name')
            ->addAttributeToFilter('visibility', 4)
            ->addCategoryFilter($category)
            ->setPageSize(10)
            ->setOrder('rand()');

        return $collection;
    }

    public function getSavings($price, $msrp)
    {
        if ($msrp-$price > 0)
        {
            return Mage::helper('core')->currency($msrp-$price);
        } else {
            return false;
        }
    }
}