<?php

class FurnitureGallery_CustomCategories_Block_Category_Range extends Mage_Core_Block_Template
{
    public function getCurrentCategory()
    {
        return Mage::registry('current_category');
    }

    public function helper()
    {
        return Mage::helper('furnituregallery_customcategories');
    }

    public function getGallery()
    {
        $category = $this->getCurrentCategory();
        $helper = $this->helper();
        $gallery = array();

        for ($i = 1;$i<=6;$i++)
        {
            if ($category->getData('range_image_'.$i))
            {
                $gallery[$i]['small_img'] = $helper->resizeCategoryImage($category->getData('range_image_'.$i), 550);

                $gallery[$i] = array_filter($gallery[$i]);
            }
        }

        return $gallery;
    }
}