<?php

namespace Adtones\Stock\Block\Adminhtml\OrderStock\Edition;

use Magento\Backend\Block\Widget\Context;

class StockOrder extends \Magento\Backend\Block\Template
{
    /**
     * @var \Magento\Backend\Block\Template\Context
     */
    protected $_context;
    /**
     * @var \Magento\Customer\Model\Session
     */
    protected $_customerSession;
    /**
     * @var \Magento\Framework\ObjectManagerInterface
     */
    protected $_objectManager;

    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Customer\Model\Session $customerSession
     * @param \Magento\Framework\ObjectManagerInterface $objectManager
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Customer\Model\Session $customerSession,  
        \Magento\Framework\ObjectManagerInterface $objectManager,
        array $data = []
     ) {
        parent::__construct($context, $data);
        $this->_customerSession = $customerSession;
        $this->_objectManager = $objectManager;
      }
}