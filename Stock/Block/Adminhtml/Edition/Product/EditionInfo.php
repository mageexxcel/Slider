<?php
namespace Adtones\Stock\Block\Adminhtml\Edition\Product;
 
class EditionInfo extends \Magento\Backend\Block\Template
{
    /**
     * AssignProducts constructor.
     *
     * @param \Magento\Backend\Block\Template\Context  $context
     * @param array                                    $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        array $data = []
    ) {
        parent::__construct($context, $data);
    }
}
?>