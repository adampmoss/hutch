<?php

class FurnitureGallery_CustomCategories_Block_Offcanvas extends Mage_Page_Block_Html_Topmenu
{
    protected function _getHtml(Varien_Data_Tree_Node $menuTree, $childrenWrapClass)
    {
        $html = '';
        $children = $menuTree->getChildren();

        foreach ($children as $child) {

            $menu_class = "";

            if ($child->hasChildren()) {
                $menu_class = "has-submenu";
            }
            $html .= '<li class="' . $menu_class . '"">';
            $html .= '<a href="' . $child->getUrl() . '"><span>'
                . $this->escapeHtml($child->getName()) . '</span></a>';

            if ($child->hasChildren()) {
                $html .= '<ul class="left-submenu">
                    <li class="back"><a href="#">'.$this->__('Back').'</a></li>';
                $html .= '<li><a href="'.$child->getUrl().'">'.$this->__('View All ').$this->escapeHtml
                    ($child->getName()).'</a></li>';
                $html .= $this->_getHtml($child, $childrenWrapClass);
                $html .= '</ul>';
            }
            $html .= '</li>';
        }

        return $html;
    }
}