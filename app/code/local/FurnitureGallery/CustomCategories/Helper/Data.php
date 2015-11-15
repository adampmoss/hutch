<?php
/**
 * Created by PhpStorm.
 * User: adammoss
 * Date: 03/09/15
 * Time: 19:40
 */ 
class FurnitureGallery_CustomCategories_Helper_Data extends Mage_Core_Helper_Abstract {

    public function resizeCategoryImage($file_name, $resize = 550)
    {
        $file_name;
        $resize_dir = 'resized-'.$resize;
        $category_dir = 'catalog'.DS.'category';
        $category_path = $_SERVER['DOCUMENT_ROOT'] . DS . 'media'. DS . $category_dir;

        $full_resized_url_path =  Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA).$category_dir.DS.$resize_dir.DS;

        if (file_exists($category_path.DS.$resize_dir.DS.$file_name)) {

            return $full_resized_url_path.$file_name;

        }
        if (file_exists($category_path.DS.$file_name)) {

            if (!is_dir($category_path.DS.$resize_dir)) {
                mkdir($category_path.DS.$resize_dir);
            }

            $_image = new Varien_Image($category_path.DS.$file_name);
            $_image->constrainOnly(true);
            $_image->keepAspectRatio(false);
            $_image->keepFrame(false);
            $_image->keepTransparency(true);
            $_image->resize($resize);
            $_image->save($category_path.DS.$resize_dir.DS.$file_name);

            $catImg = $full_resized_url_path.$file_name;
        }

        return  $catImg;
    }

    public function getCategoryLowestPrice($category) {

        $price = Mage::getModel('catalog/product')->getCollection()
            ->addCategoryFilter($category)
            ->addAttributeToSelect('final_price')
            ->addAttributeToFilter('visibility', array('neq' => 1))
            ->addAttributeToFilter('status', array('eq' => 1))
            ->addAttributeToSort('price', 'asc')
            ->setPageSize(1)
            ->getFirstItem()
            ->getFinalPrice();

        if ($price > 0)
        {
            return Mage::helper('core')->currency($price);
        }

        return false;
    }

}