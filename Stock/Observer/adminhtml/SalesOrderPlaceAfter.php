<?php
namespace Adtones\Stock\Observer\adminhtml;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\Exception\LocalizedException;

class SalesOrderPlaceAfter implements \Magento\Framework\Event\ObserverInterface
{
    /**
     * @var \Magento\Sales\Api\Data\OrderInterface
     */
    protected $_order;
    /**
     * @var \Magento\Sales\Model\Order
     */
    protected $_orderModel;
    /**
     * @var \Adtones\Stock\Model\StockFactory
     */
    protected $_stockFactory;
    /**
     * @var \Adtones\Stock\Model\ReportFactory
     */
    protected $_reportFactory;
    /**
     * @var \Adtones\Stock\Helper\Data
     */
    protected $_helper;
    /**
     * @var \Adtones\Stock\Helper\Data
     */
    protected $_messageManager;
    protected $orderRepository;

    /**
     * Execute observer
     *
     * @param \Magento\Sales\Model\Order $orderModel
     * @param \Adtones\Stock\Model\StockFactory $stockFactory
     * @param \Adtones\Stock\Model\ReportFactory $reportFactory
     * @param \Adtones\Stock\Helper\Data $helper
     * @param \Magento\Framework\Message\ManagerInterface $messageManager
     */
    public function __construct(
        \Magento\Sales\Model\Order $orderModel,
        \Adtones\Stock\Model\StockFactory $stockFactory,
        \Adtones\Stock\Model\ReportFactory $reportFactory,
        \Adtones\Stock\Helper\Data $helper,
        \Magento\Sales\Api\OrderRepositoryInterface $orderRepository,
        \Magento\Framework\Message\ManagerInterface $messageManager
    ){
        $this->_orderModel     = $orderModel;
        $this->_stockFactory   = $stockFactory;
        $this->_reportFactory  = $reportFactory;
        $this->_helper         = $helper;
        $this->_messageManager = $messageManager;
        $this->orderRepository = $orderRepository;
    }

    /**
     *
     * @param \Magento\Framework\Event\Observer $observer
     * @return void
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        // To get order details using event manager
        $orderCollection  = $observer->getEvent()->getOrder();
        $orderItems = $orderCollection->getAllItems();

        // To check product details in Stock Table.
        $stockCollection = $this->_stockFactory->create();

        // To insert Data into Report Grid.
        $reportCollection = $this->_reportFactory->create();

        foreach ($orderItems as $orderItem) {
            $productId = $orderItem->getProductId();
            $countCollection = $this->_helper->checkProductInStockCollection($productId);
            
            if($countCollection){
                $productSku = $orderItem->getSku();

                // Getting product attribute using product Id.
                $productCollection = $this->_helper->getProductAttribute($productId);
                $productArtist = $productCollection->getProductArtist();
                $productWork = $productCollection->getProductWork();

                // Getting Details from order.
                $reportGridArray = array(
                    'order_id'       => $orderCollection->getIncrementId(),
                    'invoice_id'     => "Not Generated",
                    'status'         => $orderCollection->getStatus(),
                    'firstname'      => $orderCollection->getCustomerFirstname(),
                    'lastname'       => $orderCollection->getCustomerLastname(),
                    'order_type'     => "Admin",
                    'artist'         => $productArtist,
                    'title_of_work'  => $productWork,
                    'sku'            => $productSku,
                    'edition_number' => 'Not Assigned',
                    'date_ordered'   => $orderCollection->getCreatedAt(),
                    'list_price'     => $orderCollection->getSubtotal(),
                    'discount'       => $orderCollection->getDiscountAmount(),
                    'price_paid'     => '$0.00'
                );

                // Saving data in report grid.
                $reportCollection->setData($reportGridArray);
                try {
                    $reportCollection->save();
                } catch (LocalizedException $e) {
                    $this->_messageManager->addErrorMessage($e->getMessage());
                } catch (\Exception $e) {
                    $this->_messageManager->addExceptionMessage($e, __('Something went wrong while saving the Report.'));
                }
            }
        }
    }
}
