<?php

namespace Excellence\Slider\Model\Adminhtml\Config\Source;
 
class Sliderposition implements \Magento\Framework\Option\ArrayInterface
{
    const TOP_LEFT = 1;
    const BOTTOM_LEFT = 2;
    const TOP_CENTER = 3;
    const BOTTOM_CENTER = 4;
    const TOP_RIGHT = 5;
    const BOTTOM_RIGHT = 6;

    public function toOptionArray()
    {

        return [['value' => self::TOP_LEFT, 'label' => __('Top Left')], 
                ['value' => self::BOTTOM_LEFT, 'label' => __('Bottom Left')],
                ['value' => self::TOP_CENTER, 'label' => __('Top Center')],
                ['value' => self::BOTTOM_CENTER, 'label' => __('Bottom Center')],
                ['value' => self::TOP_RIGHT, 'label' => __('Top Right')],
                ['value' => self::BOTTOM_RIGHT, 'label' => __('Bottom Right')],
                ];

    }
}