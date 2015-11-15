<?php

class FurnitureGallery_Homepage_Block_Favourites extends Mage_Core_Block_Template
{
    public function configPath($field)
    {
        return Mage::getStoreConfig('furnituregallery_homepage/favourites/'.$field);
    }

    public function getFavourites()
    {
        $data = array();

        for ($i = 1;$i<=3;$i++)
        {
            if ($image = $this->configPath('image_'.$i))
            {
                $data[$i]['image'] = $image;
                $data[$i]['link'] = $this->configPath('link_'.$i);
                $data[$i]['text'] = $this->configPath('text_'.$i);
            }
        }

        return $data;
    }
}