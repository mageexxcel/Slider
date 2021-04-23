<?php

namespace Adtones\Stock\Observer\adminhtml;

use Exception;
use Magento\Sales\Api\Data\InvoiceInterface;
use Magento\Sales\Api\InvoiceRepositoryInterface;
use Magento\Framework\Event\ObserverInterface;

class SalesOrderInvoice implements ObserverInterface 
{
    /**
     * @var \Magento\Framework\Registry
     */
   protected $_registry;
    /**
     * @var InvoiceRepositoryInterface
     */
    private $invoiceRepository;
    /**
     * @var \Adtones\Stock\Model\ReportFactory
     */
    protected $_reportCollection;
    /**
     * @var \Adtones\Stock\Helper\Data
     */
    protected $_helper;

    /**
     * @param \Magento\Framework\Registry $registry
     * @param InvoiceRepositoryInterface $invoiceRepository
     * @param \Adtones\Stock\Model\ReportFactory $reportCollection
     * @param \Adtones\Stock\Helper\Data $helper
     */
    public function __construct(
        \Magento\Framework\Registry $registry,
        InvoiceRepositoryInterface $invoiceRepository,
        \Adtones\Stock\Model\ReportFactory $reportCollection,
        \Adtones\Stock\Helper\Data $helper
    ){
        $this->_registry = $registry;
        $this->invoiceRepository = $invoiceRepository;
        $this->_reportCollection = $reportCollection;
        $this->_helper = $helper;
    }

    public function execute(\Magento\Framework\Event\Observer $observer) 
    {
        try{
            $invoice = $observer->getEvent()->getInvoice();
            $invoiceId = $invoice->getIncrementId();
            $orderId = $invoice->getOrderId();

            $orderIncrementId = $this->_helper->getOrderIncrementId($orderId)->getIncrementId();

            $reportModel = $this->_reportCollection->create();
            $reportCollection = $reportModel->getCollection()->addFieldToFilter('order_id', array('eq' => $orderIncrementId));

            // Updating report collection model
            if($reportCollection->getSize() >= 1){
                foreach ($reportCollection as $report) {
                    $reportModel->load($report->getReportId());
                    $reportModel->setInvoiceId($invoiceId);
                    $reportModel->save();
                }
            }
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
        }
    }
} 