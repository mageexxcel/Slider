<?php

namespace Excellence\Slider\Model\Adminhtml\Config\Source;
 
class Status implements \Magento\Framework\Option\ArrayInterface
{
    const STATUS_ENABLED = 1;
    const STATUS_DISABLED = 2;

    /**
     * get available statuses.
     *
     * @return []
     */
    public function toOptionArray()
    {
        return [['value' => self::STATUS_ENABLED, 'label' => __('Enabled')], 
                ['value' => self::STATUS_DISABLED, 'label' => __('Disabled')]
                ];
    }
}