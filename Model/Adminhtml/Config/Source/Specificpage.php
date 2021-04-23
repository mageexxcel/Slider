<?php

namespace Excellence\Slider\Model\Adminhtml\Config\Source;
 
class Specificpage extends \Magento\Backend\Block\Widget\Grid\Column\Renderer\AbstractRenderer
{
    protected $_sliderpagesFactory;

    const CMS_PAGE = 1;
    const CATEGORY_PAGE = 2;
    const PRODUCT_PAGE = 3;
    const CHECKOUT_PAGE = 4;
    const CART_PAGE = 5;
    
    public function __construct(
        \Magento\Backend\Block\Context $context,
        \Excellence\Slider\Model\SliderpagesFactory $sliderpagesFactory
    )
    {
        $this->_sliderpagesFactory = $sliderpagesFactory;
        parent::__construct($context);
    }

    public function render(\Magento\Framework\DataObject $row)
    {
        $id = $row->getData($this->getColumn()->getIndex());
        $data = $this->_sliderpagesFactory->create()->load($id);

        if($data->getSliderDisplayPage() == 1){
            return [['value' => self::CMS_PAGE, 'label' => __('CMS Page')], 
                ['value' => self::CATEGORY_PAGE, 'label' => __('Category Page')],
                ['value' => self::PRODUCT_PAGE, 'label' => __('Product Page')],
                ['value' => self::CHECKOUT_PAGE, 'label' => __('Checkout Page')],
                ['value' => self::CART_PAGE, 'label' => __('Cart Page')]
                ];
        }

    }
}
