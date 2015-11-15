<?php
/**
 * Created by PhpStorm.
 * User: adammoss
 * Date: 28/10/15
 * Time: 20:57
 */ 
class FurnitureGallery_ShippingMessages_Helper_Data extends Mage_Core_Helper_Abstract
{
    public $_items;

    public function __construct()
    {
        $this->_items = Mage::getSingleton('checkout/session')->getQuote()->getAllItems();
    }

    public function totalWeightInCart()
    {
        $weight = 0;
        foreach($this->_items as $item) {
            $weight += ($item->getWeight() * $item->getQty()) ;
        }

        return $weight;
    }

    public function isProductOver50kg($product)
    {
        if ($product->getWeight() >= 50)
        {
            return true;
        }

        return false;
    }

    public function checkAnyItemsWeightOver50kg()
    {
        foreach($this->_items as $item) {
            if ($item->getWeight() >= 50)
            {
                return true;
            }
        }

        return false;
    }
}