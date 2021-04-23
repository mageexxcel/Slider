<?php

namespace Excellence\Slider\Model\Adminhtml\Config\Source;
 
class Stretchimage implements \Magento\Framework\Option\ArrayInterface
{
    const STATUS_ACTIVE = 1;
    const STATUS_INACTIVE = 0;

    /**
     * get available statuses.
     *
     * @return []
     */
    public function toOptionArray()
    {
        return [['value' => self::STATUS_ACTIVE, 'label' => __('Yes')], 
                ['value' => self::STATUS_INACTIVE, 'label' => __('No')]
                ];
    }
}