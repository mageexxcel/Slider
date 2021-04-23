<?php

namespace Excellence\Slider\Model\Adminhtml\Config\Source;
 
class Autoplay implements \Magento\Framework\Option\ArrayInterface
{
    const ENABLE = 1;
    const DISABLE = 0;

    public function toOptionArray()
    {
        return [['value' => null, 'label' => __('-- Select Page --')],
                ['value' => self::ENABLE, 'label' => __('Enable')], 
                ['value' => self::DISABLE, 'label' => __('Disable')],
                ];            
    }   
}