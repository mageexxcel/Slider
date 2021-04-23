<?php

namespace Adtones\Stock\Controller\Adminhtml\Allocate;

class RegistrySetupForGrid extends \Adtones\Stock\Controller\Adminhtml\OrderStock
{
    /**
     * @var \Magento\Framework\Registry
     */
    protected $_registry;
    /**
     * @var \Adtones\Stock\Model\StockFactory
     */
    protected $_stockCollection;
    /**
     * @var \Adtones\Stock\Model\ReportFactory
     */
    protected $_reportCollection;
    /**
     * @var \Adtones\Stock\Helper\Data
     */
    protected $_customHelper;

    /**
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\App\Request\Http $request
     * @param \Adtones\Stock\Model\StockFactory $stockCollection
     * @param \Adtones\Stock\Model\ReportFactory $reportCollection
     * @param \Adtones\Stock\Helper\Data $customHelper
     * @param \Magento\Framework\Registry $registry
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\App\Request\Http $request,
        \Adtones\Stock\Model\StockFactory $stockCollection,
        \Adtones\Stock\Model\ReportFactory $reportCollection,
        \Adtones\Stock\Helper\Data $customHelper,
        \Magento\Framework\Registry $registry
    ) {
        $this->request = $request;
        $this->_stockCollection = $stockCollection;
        $this->_reportCollection = $reportCollection;
        $this->_customHelper = $customHelper;
        $this->_registry = $registry;
        parent::__construct($context, $registry);
    }

    /**
     * New action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        $resultRedirect->setPath('adtones_stock/orderstock/index');

        // Edition stock collection
        $stockModel = $this->_stockCollection->create();

        // Edition Report collection
        $reportModel = $this->_reportCollection->create();

        $currentProductId = "";
        if($this->request->getParam('current_product_id') && $this->request->getParam('order_number')){
            // Setting up cookie to fetch product id on stock grid controller.
            $productCookieName = "allocate_product_id";
            $productCookieValue = $this->request->getParam('current_product_id');
            setcookie($productCookieName, $productCookieValue, time() + (86400 * 30), "/");

            // Setting up cookie to fetch order id on stock grid controller.
            $orderCookieName = "order_number";
            $orderCookieValue = $this->request->getParam('order_number');
            setcookie($orderCookieName, $orderCookieValue, time() + (86400 * 30), "/");

            // Setting up cookie to fetch order id on stock grid controller.
            $stockCookieName = "stock_item_id";
            $stockCookieValue = $this->request->getParam('stock_item_id');
            setcookie($stockCookieName, $stockCookieValue, time() + (86400 * 30), "/");

            // Checking if product edition already assigned to order.
            $allocatedCollection = $stockModel->getCollection()->addFieldToFilter('availability', array('eq' => 'Allocated'))
                                                                ->addFieldToFilter('allocated', array('eq' => 1))
                                                                ->addFieldToFilter('allocated_order_id', array('eq' => $orderCookieValue))
                                                                ->addFieldToFilter('product_id', array('eq' => $productCookieValue))
                                                                ->addFieldToFilter('edition_id', array('like' => '%'.$stockCookieValue.'%'));

            // If exist then 
            if($allocatedCollection->getSize() >= 1){
                $indexArray = array();

                // If exist row will be update.
                $stockDataId = '';
                foreach ($allocatedCollection as $stockData) {
                    $stockDataId = $stockData->getStockId();
                    $indexArray = json_decode($stockData->getEditionId(), true);
                }

                // Removing current index from array.
                foreach ($indexArray as $index => $value) {
                    if($value == $stockCookieValue){
                        unset($indexArray[$index]);
                    }
                }

                $stockModel->load($stockDataId);

                $orderData = $this->_customHelper->getOrderIncrementId($stockModel->getAllocatedOrderId());
                // Checking if product edition already assigned to order.
                $allocatedCollection = $reportModel->getCollection()->addFieldToFilter('order_id', array('eq' => $orderData->getIncrementId()))
                                                                    ->addFieldToFilter('sku', array('eq' => $stockModel->getSku()))
                                                                    ->addFieldToFilter('edition_number', array('eq' => $stockModel->getEditionNumber()))
                                                                    ->addFieldToFilter('stock_item_id', array('eq' => $stockCookieValue));

                // Deleting collection while reallocating product from order.
                if($allocatedCollection->getSize() > 0 ){
                    $allocatedCollection->walk('delete');
                }
                
                if($indexArray){
                    $stockModel->setEditionId(json_encode(array_values($indexArray)));
                }else{
                    // Setting up available status in edition.
                    $stockModel->setEditionId("");
                    $stockModel->setAvailability("Available");
                    $stockModel->setAllocated("");
                    $stockModel->setAllocatedOrderId("");
                    $stockModel->setEditionId("");
                }
                $stockModel->save();
            }
        }
        return $resultRedirect;
    }
}
