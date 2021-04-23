<?php

namespace Excellence\Slider\Model\Adminhtml\Config\Source;
 
class Attributes implements \Magento\Framework\Option\ArrayInterface
{
    public function toOptionArray()
    {
        return [
            'bxslider' => 'BxSlider'
            ,'flexslider' => 'FlexSlider'
            ,'owlcarousel' => 'OwlCarousel'
            ,'unslider' => 'UnSlider'
            ];
    }
}
