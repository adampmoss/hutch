<?php

class FurnitureGallery_Homepage_Block_Boxes extends Mage_Core_Block_Template
{
    public function configPath($field)
    {
        return Mage::getStoreConfig('furnituregallery_homepage/boxes/'.$field);
    }

    public function getBoxes()
    {
        $data = array();

        for ($i = 1;$i<=9;$i++)
        {
            if ($image = $this->configPath('image_'.$i))
            {
                $data[$i]['image'] = $image;
                $data[$i]['link'] = $this->configPath('link_'.$i);
            }
        }

        return $data;
    }
}