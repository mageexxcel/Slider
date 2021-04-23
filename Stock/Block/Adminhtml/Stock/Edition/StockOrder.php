<?php

namespace Adtones\Stock\Block\Adminhtml\Stock\Edition;

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
     * @var \Adtones\Stock\Model\StockFactory
     */
    protected $_stockCollection;

    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Customer\Model\Session $customerSession
     * @param \Magento\Framework\ObjectManagerInterface $objectManager
     * @param \Adtones\Stock\Model\StockFactory $stockCollection
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Customer\Model\Session $customerSession,  
        \Magento\Framework\ObjectManagerInterface $objectManager,
        \Adtones\Stock\Model\StockFactory $stockCollection,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->_customerSession = $customerSession;
        $this->_objectManager = $objectManager;
        $this->_stockCollection = $stockCollection;
    }

    /**
     * New action
     *
     * @return product edition number
     */
    public function getAllocationDetails($order_id, $productId, $i){
        if($order_id && $productId && $i){
            $stockModel = $this->_stockCollection->create()->getCollection()->addFieldToFilter('allocated_order_id', array('eq' => $order_id))
                                                                            ->addFieldToFilter('product_id', array('eq' => $productId))
                                                                            ->addFieldToFilter('allocated', array('eq' => 1))
                                                                            ->addFieldToFilter('edition_id', array('like' => '%'.$i.'%'));

            foreach ($stockModel as $model) {
                return $model->getEditionNumber();
            }
            return false;
        }
    }

    /**
     * New action
     *
     * @return product edition number
     */
    public function getCollectionSize($productId){
        if($productId){
            $stockModel = $this->_stockCollection->create()->getCollection()->addFieldToFilter('product_id', array('eq' => $productId));
            return $stockModel->getSize();
        }
    }

    /**
     * New action
     *
     * @return product edition number
     */
    public function isProductExists($productId){
        $stockModel = $this->_stockCollection->create()->getCollection()->addFieldToFilter('product_id', array('eq' => $productId));
        if($stockModel->getSize() >= 1) {
            return true;
        }
        return false;
    }
}