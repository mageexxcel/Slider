<?php

namespace Excellence\Slider\Model\Adminhtml\Config\Source;
 
class Fades implements \Magento\Framework\Option\ArrayInterface
{
    const FADE_IN = 1;
    const FADE_OUT = 0;

    public function toOptionArray()
    {
        return [['value' => null, 'label' => __('-- Select Page --')],
                ['value' => self::FADE_IN, 'label' => __('Fade In')], 
                ['value' => self::FADE_OUT, 'label' => __('Fade Out')],
                ];           
    }   
}