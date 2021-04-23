<?php

namespace Adtones\Stock\Observer\adminhtml;

use Magento\Framework\Event\ObserverInterface;

class SalesOrderStateChange implements ObserverInterface 
{
    /**
    * @var \Magento\Framework\Registry
    */
    protected $_registry;
    /**
     * @var \Magento\Sales\Api\OrderRepositoryInterface
     */
    protected $_orderRepository;
    /**
     * @var \Adtones\Stock\Model\ReportFactory
     */
    protected $_reportCollection;

    /** 
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Sales\Api\OrderRepositoryInterface $orderRepository
     * @param \Adtones\Stock\Model\ReportFactory $reportCollection
     */
    public function __construct(
        \Magento\Framework\Registry $registry,
        \Magento\Sales\Api\OrderRepositoryInterface $orderRepository,
        \Adtones\Stock\Model\ReportFactory $reportCollection
    ){
        $this->_registry = $registry;
        $this->_orderRepository = $orderRepository;
        $this->_reportCollection = $reportCollection;
    }

    public function execute(\Magento\Framework\Event\Observer $observer) 
    {
        $order = $observer->getEvent()->getOrder();
        $orderId = $order->getIncrementId(); 
        $OrderStatus = $order->getStatus();

        // Report model collection.
        $reportModel = $this->_reportCollection->create();
        try{
            $reportCollection = $reportModel->getCollection()->addFieldToFilter('order_id', array('eq' => $orderId));

            // Updating report collection model
            if($reportCollection->getSize() >= 1){
                foreach ($reportCollection as $report) {
                $reportModel->load($report->getReportId());
                    if($OrderStatus == 'complete'){
                        // if order status complete then price will be paid.
                        $paidPrice = $reportModel->getListPrice();
                        $reportModel->setPricePaid($paidPrice);
                        $reportModel->setListPrice("0.00");
                    }
                    $reportModel->setStatus($OrderStatus);
                    $reportModel->save();
                }
            }
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
        }
    }
} 