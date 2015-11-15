<?php class FurnitureGallery_CustomCategories_ProductController extends Mage_Core_Controller_Front_Action
{
    public function indexAction()
    {
        $product = Mage::getModel('catalog/product')
            ->setStoreId(Mage::app()->getStore()->getId())
            ->load($this->getRequest()->getPost('id'));
        Mage::register('product', $product);
        Mage::register('current_product', $product);

        $this->loadLayout();
        $this->renderLayout();
    }
}