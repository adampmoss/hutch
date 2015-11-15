<?php

class FurnitureGallery_Homepage_Block_Slider extends Mage_Core_Block_Template
{
    public function configPath($field)
    {
        return Mage::getStoreConfig('furnituregallery_homepage/slider/'.$field);
    }

    public function getSlides()
    {
        $data = array();

        for ($i = 1;$i<=6;$i++)
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