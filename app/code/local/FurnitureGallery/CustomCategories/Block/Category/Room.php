<?php

class FurnitureGallery_CustomCategories_Block_Category_Room extends Mage_Core_Block_Template
{
    protected $_categoryImgPath;

    public function getCurrentCategory()
    {
        return Mage::registry('current_category');
    }

    public function helper()
    {
        return Mage::helper('furnituregallery_customcategories');
    }

    public function getFeaturedRanges()
    {
        $category = $this->getCurrentCategory();
        $categories = array();

        for ($i = 1;$i<=6;$i++)
        {
            if ($category->getData('featured_range_'.$i))
            {
                $categories[$i] = Mage::getModel('catalog/category')->load($category->getData('featured_range_'.$i));
            }
        }

        /*$categories = Mage::getModel('catalog/category')
            ->getCollection()
            ->addFieldToFilter('entity_id', array('in'=> $categoryIds))
            ->addAttributeToSelect('name')
            ->addAttributeToSelect('image')
            ->addAttributeToSelect('url');*/
        return $categories;
    }

    public function getSubCategories($_currentCategory)
    {
        $collection = $_currentCategory->getCollection();
        $collection->addAttributeToSelect('url_key')
            ->addAttributeToSelect('name')
            ->addAttributeToSelect('image')
            ->addAttributeToSelect('is_anchor')
            ->setOrder('position', Varien_Db_Select::SQL_ASC)
            ->addIdFilter($_currentCategory->getChildren())
            ->joinUrlRewrite();

        return $collection;
    }

    public function getImage($image)
    {
        if ($image)
        {
            return $this->helper()->resizeCategoryImage($image, 200);
        } else {
            return false;
            //return $this->getSkinUrl('images/logo-fade.jpg');
        }
    }
}