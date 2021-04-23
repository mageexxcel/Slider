<?php


namespace Adtones\Stock\Helper;

use Magento\Framework\App\Helper\AbstractHelper;

class Data extends AbstractHelper
{
    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $_storeManager;
    /**
     * @var \Magento\Sales\Model\OrderFactory
     */
    protected $_order;
    /**
     * @var \Magento\Catalog\Model\ProductFactory
     */
    protected $_productFactory;
    /**
     * @var \Adtones\Stock\Model\StockFactory
     */
    protected $_stockFactory;
    /**
     * @var \Adtones\Stock\Model\ReportFactory
     */
    protected $_reportFactory;
    /**
     * @var \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory 
     */
    protected $_productCollectionFactory;
    /**
     * @var Magento\Catalog\Model\Category
     */
    protected $_categories;
    /**
     * @var \Magento\Sales\Model\Order\Invoice
     */
    protected $_invoice;
    /**
     * @var \Magento\CatalogInventory\Model\Stock\StockItemRepository
     */
    protected $_stockItemRepository;
    /**
     * @var \Magento\Catalog\Model\ProductRepository
     */
    protected $_productRepository;
    /**
     * @var \Magento\CatalogInventory\Api\StockRegistryInterface
     */
    protected $_StockRegistryInterface;

    /**
     * @param \Magento\Framework\App\Helper\Context $context
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Sales\Model\OrderFactory $order
     * @param \Magento\Catalog\Model\ProductFactory $productFactory
     * @param \Adtones\Stock\Model\StockFactory $stockFactory
     * @param \Adtones\Stock\Model\ReportFactory $reportFactory
     * @param \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory
     * @param \Magento\Catalog\Model\Category $categories
     * @param \Magento\Sales\Model\Order\Invoice $invoice
     * @param \Magento\CatalogInventory\Model\Stock\StockItemRepository
     * @param \Magento\Catalog\Model\ProductRepository
     * @param \Magento\CatalogInventory\Api\StockRegistryInterface
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Sales\Model\OrderFactory $order,
        \Magento\Catalog\Model\ProductFactory $productFactory,
        \Adtones\Stock\Model\StockFactory $stockFactory,
        \Adtones\Stock\Model\ReportFactory $reportFactory,
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory,
        \Magento\Catalog\Model\Category $categories,
        \Magento\Sales\Model\Order\Invoice $invoice,
        \Magento\CatalogInventory\Model\Stock\StockItemRepository $stockItemRepository,
        \Magento\Catalog\Model\ProductRepository $productRepository,
        \Magento\CatalogInventory\Api\StockRegistryInterface $StockRegistryInterface
    ) {
        $this->_storeManager   = $storeManager;
        $this->_order          = $order;
        $this->_productFactory = $productFactory;
        $this->_stockFactory   = $stockFactory;
        $this->_reportFactory  = $reportFactory;
        $this->_productCollectionFactory = $productCollectionFactory;  
        $this->_categories     = $categories; 
        $this->_invoice        = $invoice;  
        $this->_stockItemRepository = $stockItemRepository;
        $this->_productRepository = $productRepository;
        $this->_StockRegistryInterface = $StockRegistryInterface;
        parent::__construct($context);
    }

    /**
     * @return bool
     */
    public function isEnabled()
    {
        return true;
    }

    /**
     * @return string
     */
    public function getStoreBaseUrl()
    {
        $baseUrl = $this->_storeManager->getStore()->getBaseUrl(); 
        return $baseUrl;
    }

    /**
     * @return string
     */
    public function getInvoiceDetails($orderId)
    {
        $invoiceIncrementID = "";
        $order = $this->_order->create()->load($orderId);
        $invoiceCollection = $order->getInvoiceCollection();
        foreach($invoiceCollection as $invoice){
            $invoiceIncrementID = $invoice->getIncrementId();
        }
        if($invoiceIncrementID){
            return $invoiceIncrementID;
        }
        return false;
    }

    /**
     * @return int
     */
    public function getProductDetail($order_id)
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $order = $objectManager->create('Magento\Sales\Model\Order')->load($order_id);
        $orderItems = $order->getAllVisibleItems();
        
        return $orderItems;
    }

    /**
     * @return int
     * to get product collection.
     */
    public function getProductAttribute($product_id)
    {
        $productFactory = $this->_productFactory->create()->load($product_id);
        return $productFactory;
    }

    /**
     * @return orderCollectionFactory
     */
    public function getOrderIncrementId($orderId)
    {
        $orderFactory =  $this->_order->create()->load($orderId);
        return $orderFactory;
    }

    /**
     * @return orderCollectionFactory
     */
    public function getOrderNormalId($orderId)
    {
        $orderFactory =  $this->_order->create()->load($orderId, 'increment_id');
        return $orderFactory->getId();
    }

    public function getEditionOrderItem($orderId , $productSku)
    {
        $orderIncrementId = $this->getOrderIncrementId($orderId);
        $orderFactory = $this->_reportFactory->create()->getCollection()->addFieldToFilter('sku', array('eq' => $productSku))
                                                                        ->addFieldToFilter('order_id', array('eq' => $orderIncrementId->getIncrementId()));
        if($orderFactory->getSize() >= 1){
            $productEditionNumber = "";
            foreach ($orderFactory as $report) {
                $productEditionNumber = $report->getEditionNumber();
            }
            return $productEditionNumber;
        }
        return ;
    }

    /**
     * @return int
     * To check product details in Stock Table.
     */
    public function checkProductInStockCollection($product_id)
    {
        $stockCollection = $this->_stockFactory->create();
        $productCollection = $stockCollection->getCollection()->addFieldToFilter('product_id', array('eq' => $product_id));
        return $productCollection->getSize();
    }

    /**
     * @return int
     * To check product details in Stock Table.
     */
    public function isEditionExist($product_sku, $edition_id, $order_id)
    {
        $stockCollection = $this->_stockFactory->create();
        $productCollection = $stockCollection->getCollection()->addFieldToFilter('sku', array('eq' => $product_sku))
                                                                ->addFieldToFilter('edition_number', array('eq' => $edition_id))
                                                                ->addFieldToFilter('allocated_order_id', array('eq' => $order_id));
        return $productCollection->getSize();
    }

    /**
     * @return int
     * To check product details in Stock Table.
     */
    public function getEditionReport($product_sku, $order_id)
    {
        $incrementIdData = $this->getOrderIncrementId($order_id);
        $incrementId = $incrementIdData->getIncrementId();
        
        $reportCollection = $this->_reportFactory->create();
        $productCollection = $reportCollection->getCollection()->addFieldToFilter('sku', array('eq' => $product_sku))
                                                                ->addFieldToFilter('order_id', array('eq' => $incrementId));
        return $productCollection;
    }

    /**
     * New action
     *
     * @return product edition number
     */
    public function getCollectionSize($sku){
        if($sku){
            $stockModel = $this->_stockFactory->create()->getCollection()->addFieldToFilter('sku', array('eq' => $sku));
            return $stockModel->getSize();
        }
    }

    /**
     * New action
     *
     * @return product category
     */
    public function getCategoriesCollection($id){
        if($id){
            $categories = $this->_categories->load($id);
            return $categories;
        }
    }

    /**
     * New action
     *
     * @return invoice id
     */
    public function getInvoiceId($incrementId){
        if($incrementId){
            $invoiceDetails = $this->_invoice->loadByIncrementId($incrementId);
            return $invoiceDetails->getId();
        }
    }

    /**
     * @return int
     * To check product details in Stock Table.
     */
    public function checkEditionStockCollection($product_id)
    {
        $stockCollection = $this->_stockFactory->create();
        $productCollection = $stockCollection->getCollection()
                                             ->addFieldToFilter('product_id', array('eq' => $product_id))
                                             ->addFieldToFilter('availability', array('eq' => "Available"));
        return $productCollection->getSize();
    }

    /**
     * @return int
     * to Increase product quantity while creating new edition.
     */
    public function getIncreaseProductQty($product_id)
    {
        $product = $this->_productRepository->getById($product_id);
        $stockItem = $this->_StockRegistryInterface->getStockItemBySku($product->getSku());
        
        // Getting Edition Number from Edition Collection.
        $editionCounts = $this->checkEditionStockCollection($product_id);
        $stockItem->setQty($editionCounts);
        if(count($editionCounts) > 0){
            $stockItem->setIsInStock((bool)$editionCounts);
        }
        $this->_StockRegistryInterface->updateStockItemBySku($product->getSku(), $stockItem);
        $stockItem->save();
    }

    /**
     * @return int
     * to Increase product quantity while creating new edition.
     */
    public function getDecreaseProductQty($product_id)
    {
        $product = $this->_productRepository->getById($product_id);
        $stockItem = $this->_StockRegistryInterface->getStockItemBySku($product->getSku());

        $currentProductQuantity = $stockItem->getQty();
        $remainQuantity = $currentProductQuantity - 1;

        if($currentProductQuantity != 0){
            $stockItem->setQty($remainQuantity);
            $this->_StockRegistryInterface->updateStockItemBySku($product->getSku(), $stockItem);
            $stockItem->save();
        }
    }
}

