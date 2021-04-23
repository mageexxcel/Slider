<?php

namespace Excellence\Slider\Model\Adminhtml\Config\Source;
 
class Loop implements \Magento\Framework\Option\ArrayInterface
{
    const ENABLED = 1;
    const DISABLED = 0;

    public function toOptionArray()
    {
        return [['value' => null, 'label' => __('-- Select Page --')],
                ['value' => self::ENABLED, 'label' => __('Enable')], 
                ['value' => self::DISABLED, 'label' => __('Disable')],
                ];            
    }   
}