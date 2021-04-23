<?php
namespace Adtones\Stock\Controller\Adminhtml\NewEdition;

use Magento\Framework\Exception\LocalizedException;

class Save extends \Magento\Backend\App\Action
{
    /**
     * @return \Magento\Framework\App\Request\DataPersistorInterface
     */
    protected $dataPersistor;
    /**
     * @return \Adtones\Stock\Model\StockFactory
     */
    protected $_stockFactory;
    /**
     * @return \Adtones\Stock\Model\ReportFactory
     */
    protected $_reportFactory;
    /**
     * @return \Adtones\Stock\Helper\Data
     */
    protected $_productHelper;

    /**
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\App\Request\DataPersistorInterface $dataPersistor
     * @param \Adtones\Stock\Model\StockFactory $stockFactory
     * @param \Adtones\Stock\Model\ReportFactory $reportFactory
     * @param \Adtones\Stock\Helper\Data $helper
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\App\Request\DataPersistorInterface $dataPersistor,
        \Adtones\Stock\Model\StockFactory $stockFactory,
        \Adtones\Stock\Model\ReportFactory $reportFactory,
        \Adtones\Stock\Helper\Data $helper
    ) {
        $this->dataPersistor  = $dataPersistor;
        $this->_stockFactory  = $stockFactory;
        $this->_reportFactory  = $reportFactory;
        $this->_productHelper = $helper;
        parent::__construct($context);
    }

    /**
     * Save action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $formData = "";
        if($this->getRequest()->getParam('formData')){
            $formData = $this->getRequest()->getParam('formData');
        }

        // Stock manager grid collection factory.
        $stockCollection = $this->_stockFactory->create();

        // Report manager grid collection factory.
        $reportCollection = $this->_reportFactory->create();

        if ($formData) {
            // Saving stock grid data.
            $editionData = json_decode($formData, true);
            $stockCollection->setData($editionData);
            $stockCollection->save();

            // Getting Order Details fro Helper.
            $orderInfo = $this->_productHelper->getOrderIncrementId($editionData['allocated_order_id']);

            // Saving report grid data.
            $reportData = array(
                'order_id'       => $orderInfo->getIncrementId(),
                'invoice_id'     => $this->_productHelper->getInvoiceDetails($editionData['allocated_order_id']), // Getting Invoice id using order number.
                'status'         => $orderInfo->getState(),
                'firstname'      => $orderInfo->getBillingAddress()->getFirstname(),
                'lastname'       => $orderInfo->getBillingAddress()->getLastname(),
                'order_type'     => 'Admin',
                'artist'         => $editionData['artist'],
                'title_of_work'  => $editionData['work'],
                'sku'            => $editionData['sku'],
                'edition_number' => $editionData['edition_number'],
                'date_ordered'   => $orderInfo->getCreatedAt(),
                'list_price'     => $orderInfo->getSubtotal(),
                'discount'       => $orderInfo->getDiscountAmount(),
                'price_paid'     => '$0.00',
                'stock_item_id'  => json_decode($editionData['edition_id'], true),
            );

            $reportCollection->setData($reportData);
            $reportCollection->save();

            // If Selected Availability Then edition quantity will be increase. 
            if($editionData['availability'] == 'Available'){
                $this->_productHelper->getIncreaseProductQty($editionData['product_id']);
            }

            // Getting Order Details fro Helper.
            $orderInfo = $this->_productHelper->getOrderIncrementId($editionData['allocated_order_id']);
        }
        // return json_encode($formData, true);
    }
}
