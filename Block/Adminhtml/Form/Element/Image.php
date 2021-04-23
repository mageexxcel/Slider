<?php

/**
 * Slider Form Image File Element Block
 *
 */
namespace Excellence\Slider\Block\Adminhtml\Form\Element;

class Image extends \Magento\Framework\Data\Form\Element\Image
{ 
    /**
     * Get image preview url
     *
     * @return string
     */
    protected function _getUrl()
    {
        return $this->getValue();
    }
}
