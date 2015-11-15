<?php

class FurnitureGallery_Attributes_Block_Viewmore extends Mage_Catalog_Block_Product_View
{

    public function getCategories($categoryIds)
    {

        $categories = Mage::getModel('catalog/category')->getCollection()
                ->addAttributeToFilter('entity_id', array('in', $categoryIds))
                ->addAttributeToSelect('name');

        return $categories;
    }

    public function getCategoryIds($product)
    {
        $max = 2;
        $ids = array();

        for ($i = 1; $i<=$max;$i++)
        {
            if ($id = $product->getData('view_more_'.$i))
            {
                $ids[] = $id;
            }
        }

        return $ids;
    }

    public function formatName($name)
    {
        $nameArray = explode(" ", $name);
        $nameArray[0] = '<span class="first-word">'.$nameArray[0].'</span>';
        return $nameArray;
    }
}