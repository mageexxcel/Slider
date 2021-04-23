<?php

namespace Excellence\Slider\Model\Adminhtml\Config\Source;
 
class Speed implements \Magento\Framework\Option\ArrayInterface
{
    const SPEED_1X = 1;
    const SPEED_3X = 3;
    const SPEED_5X = 5;
    const SPEED_7X = 7;

    public function toOptionArray()
    {
        return [['value' => null, 'label' => __('-- Select Page --')],
                ['value' => self::SPEED_1X, 'label' => __('1X Speed')], 
                ['value' => self::SPEED_3X, 'label' => __('3X Speed')],
                ['value' => self::SPEED_5X, 'label' => __('5X Speed')], 
                ['value' => self::SPEED_7X, 'label' => __('7X Speed')],
                ];         
    }   
}