<?php

namespace Excellence\Slider\Model\Adminhtml\Config\Source;
 
class Dots implements \Magento\Framework\Option\ArrayInterface
{
    const YES = 1;
    const NO = 0;

    public function toOptionArray()
    {
        return [['value' => null, 'label' => __('-- Select Page --')],
                ['value' => self::YES, 'label' => __('Yes')], 
                ['value' => self::NO, 'label' => __('No')],
                ];            
    }   
}