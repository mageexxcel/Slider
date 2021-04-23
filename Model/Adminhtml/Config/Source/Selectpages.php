<?php

namespace Excellence\Slider\Model\Adminhtml\Config\Source;
 
class Selectpages implements \Magento\Framework\Option\ArrayInterface
{
    const CMS_PAGE = 1;
    const CATEGORY_PAGE = 2;
    const PRODUCT_PAGE = 3;
    const CUSTOME_PAGE = 4;

    
    public function toOptionArray()
    {
        return [['value' => null, 'label' => __('-- Select Page --')],
                ['value' => self::CMS_PAGE, 'label' => __('CMS Page')], 
                ['value' => self::CATEGORY_PAGE, 'label' => __('Category Page')],
                ['value' => self::PRODUCT_PAGE, 'label' => __('Product Page')],
                ['value' => self::CUSTOME_PAGE, 'label' => __('Custom Page')],
                ];            
    }   
}