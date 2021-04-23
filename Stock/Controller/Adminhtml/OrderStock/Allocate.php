<?php


namespace Adtones\Stock\Controller\Adminhtml\OrderStock;

class Allocate extends \Adtones\Stock\Controller\Adminhtml\OrderStock
{
    /**
     * @var \Magento\Backend\Model\View\Result\ForwardFactory
     */
    protected $resultForwardFactory;
    /**
     * @var \Adtones\Stock\Model\StockFactory
     */
    protected $_stockCollection;
    /**
     * @var \Adtones\Stock\Model\ReportFactory
     */
    protected $_reportCollection;
    /**
     * @var \Magento\Framework\App\Request\Http
     */
    protected $request;
    /**
     * @var \Magento\Framework\Registry
     */
    protected $_registry;
    /**
     * @var \Magento\Sales\Model\Order
     */
    protected $_orderModel;

    /**
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\Registry $coreRegistry
     * @param \Magento\Backend\Model\View\Result\ForwardFactory $resultForwardFactory
     * @param \Adtones\Stock\Model\StockFactory $stockCollection
     * @param \Adtones\Stock\Model\ReportFactory $reportCollection
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Sales\Model\Order $orderModel
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Registry $coreRegistry,
        \Magento\Backend\Model\View\Result\ForwardFactory $resultForwardFactory,
        \Adtones\Stock\Model\StockFactory $stockCollection,
        \Adtones\Stock\Model\ReportFactory $reportCollection,
        \Magento\Framework\App\Request\Http $request,
        \Adtones\Stock\Helper\Data $customHelper,
        \Magento\Framework\Registry $registry,
        \Magento\Sales\Model\Order $orderModel
    ) {
        $this->resultForwardFactory = $resultForwardFactory;
        $this->_stockCollection = $stockCollection;
        $this->_reportCollection = $reportCollection;
        $this->request = $request;
        $this->_customHelper = $customHelper;
        $this->_registry = $registry;
        $this->_orderModel = $orderModel;
        parent::__construct($context, $coreRegistry);
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

        $orderId = $_COOKIE['order_number'];
        $stockItemId = $_COOKIE['stock_item_id'];
        $productCurrentId = $_COOKIE['allocate_product_id'];

        // Edition stock collection
        $stockModel = $this->_stockCollection->create();
        // Report stock collection
        $reportModel = $this->_reportCollection->create();

        $stockId = "";
        if($this->request->getParam('stock_id')){
            $stockId = $this->request->getParam('stock_id');
        }

        // Checking if product edition already assigned to order.
        $allocatedCollection = $stockModel->getCollection()->addFieldToFilter('availability', array('eq' => 'Allocated'))
                                                           ->addFieldToFilter('allocated', array('eq' => 1))
                                                           ->addFieldToFilter('allocated_order_id', array('eq' => $orderId))
                                                           ->addFieldToFilter('product_id', array('eq' => $productCurrentId))
                                                           ->addFieldToFilter('stock_id', array('eq' => $stockId));

        // If exist then 
        if($allocatedCollection->getSize() >= 1){
            $stockIdToSame = '';
            $editionArray = array();
            foreach ($allocatedCollection as $allocateData) {
                $stockIdToSame = $allocateData->getStockId();
                $itemEditionNumber = json_decode($allocateData->getEditionId());

                if(is_array($itemEditionNumber)){
                    foreach ($itemEditionNumber as $itemIndex) {
                        array_push($editionArray, $itemIndex);
                    }
                }else{
                    array_push($editionArray, $itemEditionNumber);
                }
            }

            // Saving model after updating.
            $stockModel->load($stockIdToSame);
            if($stockModel->getAvailability() == 'Allocated'){
              
                // Checking if index id already exists in stock collection.
                $flag = true;
                foreach ($editionArray as $previousItemIndex) {
                    if($previousItemIndex == $stockItemId){
                        $flag = false;
                    }
                }
                
                // If not, pushing it into item index column (Edition_Id).
                if($flag){
                    array_push($editionArray, $stockItemId);
                }
                $stockModel->setEditionId(json_encode($editionArray));
                $stockModel->save();
            }   
        }

        // Setting up Values in Stock Table.
        $stockModel->load($stockId);
        if($stockModel->getAvailability() == 'Available'){
            $stockModel->setAvailability("Allocated");
            $stockModel->setAllocated(1);
            $stockModel->setAllocatedOrderId($orderId);

            $editionArray = array();
            array_push($editionArray, $stockItemId);
            $stockModel->setEditionId(json_encode($editionArray));
            $stockModel->save();

            // Decreasing Quantity here.
            $this->_customHelper->getDecreaseProductQty($productCurrentId);
        }

        // Saving Report data. 
        $OrderIncrementId = $this->_customHelper->getOrderIncrementId($orderId);
        $allocatedCollection = $reportModel->getCollection()->addFieldToFilter('order_id', array('eq' => $OrderIncrementId->getIncrementId()))
                                                            ->addFieldToFilter('sku', array('eq' => $stockModel->getSku()))
                                                            ->addFieldToFilter('stock_item_id', array('eq' => $stockItemId));
        if($allocatedCollection->getSize() >= 1){
            // If exist row will be update.
            $reportNumber = '';
            foreach ($allocatedCollection as $reports) {
                $reportNumber = $reports->getReportId();
            }
            $OrderIncrementId = $this->_customHelper->getOrderIncrementId($orderId);
            $reportModel->load($reportNumber, 'report_id');
            $reportModel->setEditionNumber($stockModel->getEditionNumber());
            $reportModel->save();
        }else{
            // If not exist then new row will be create.
            $orderCollection  = $this->_orderModel->load($orderId); 
            $stockProductSku = $stockModel->load($stockId)->getSku();

            $orderItems = $orderCollection->getAllItems();
            foreach ($orderItems as $orderItem) {

                // Checking for same product to update info in report grid.
                if($stockProductSku === $orderItem->getSku()){
                    $productId = $orderItem->getProductId();
                    $countCollection = $this->_customHelper->checkProductInStockCollection($productId);
                    
                    if($countCollection){
                        $productSku = $orderItem->getSku();

                        // Getting product attribute using product Id.
                        $productCollection = $this->_customHelper->getProductAttribute($productId);
                        $productArtist = $stockModel->getArtist();
                        $productWork = $stockModel->getWork();

                        $isExistEdition = $this->_customHelper->isEditionExist($productSku, $stockModel->getEditionNumber(), $orderId);
                        // Getting Details from order.
                        if($isExistEdition){
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
                                'edition_number' => $stockModel->getEditionNumber(),
                                'date_ordered'   => $orderCollection->getCreatedAt(),
                                'list_price'     => $orderCollection->getSubtotal(),
                                'discount'       => $orderCollection->getDiscountAmount(),
                                'price_paid'     => '$0.00',
                                'stock_item_id'  => $stockItemId
                            );
                            $reportModel->setData($reportGridArray);
                            $reportModel->save();
                            $this->messageManager->addSuccessMessage(__('You saved the Edition.'));
                        }else{
                            $this->messageManager->addSuccessMessage(__('This product edition has already been assigned to other order.'));
                        }
                    }
                }
            }
        }
        
        try {
            $stockModel->save();
            return $resultRedirect->setPath('sales/order/view/order_id/'.$orderId.'/');
        } catch (LocalizedException $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
        } catch (\Exception $e) {
            $this->messageManager->addExceptionMessage($e, __('Something went wrong while saving the Edition.'));
        }
    }
}
