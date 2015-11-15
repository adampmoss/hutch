<?php

class FurnitureGallery_MoreInRange_Block_Items extends Mage_Catalog_Block_Product_List_Related
{
    public function getItems()
    {
        $product = Mage::registry('product');

        if ($categoryId = $product->getMoreInRange())
        {
            $category = Mage::getModel('catalog/category')->load($categoryId);
            $collection = Mage::getModel('catalog/product')->getCollection()
                ->addAttributeToSelect('image')
                ->addAttributeToSelect('price')
                ->addAttributeToSelect('msrp')
                ->addAttributeToSelect('name')
                ->addAttributeToFilter('visibility', 4)
                ->addCategoryFilter($category)
                ->addAttributeToFilter('entity_id', array('neq' => $product->getId()))
                ->setOrder('rand()');

            return $collection;

        } else {
            return false;
        }
    }
}