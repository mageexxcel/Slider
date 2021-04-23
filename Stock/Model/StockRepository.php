<?php


namespace Adtones\Stock\Model;

use Adtones\Stock\Api\StockRepositoryInterface;
use Adtones\Stock\Api\Data\StockSearchResultsInterfaceFactory;
use Adtones\Stock\Api\Data\StockInterfaceFactory;
use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Reflection\DataObjectProcessor;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Adtones\Stock\Model\ResourceModel\Stock as ResourceStock;
use Adtones\Stock\Model\ResourceModel\Stock\CollectionFactory as StockCollectionFactory;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\Api\ExtensionAttribute\JoinProcessorInterface;
use Magento\Framework\Api\ExtensibleDataObjectConverter;

class StockRepository implements StockRepositoryInterface
{

    protected $resource;

    protected $stockFactory;

    protected $stockCollectionFactory;

    protected $searchResultsFactory;

    protected $dataObjectHelper;

    protected $dataObjectProcessor;

    protected $dataStockFactory;

    protected $extensionAttributesJoinProcessor;

    private $storeManager;

    private $collectionProcessor;

    protected $extensibleDataObjectConverter;

    /**
     * @param ResourceStock $resource
     * @param StockFactory $stockFactory
     * @param StockInterfaceFactory $dataStockFactory
     * @param StockCollectionFactory $stockCollectionFactory
     * @param StockSearchResultsInterfaceFactory $searchResultsFactory
     * @param DataObjectHelper $dataObjectHelper
     * @param DataObjectProcessor $dataObjectProcessor
     * @param StoreManagerInterface $storeManager
     * @param CollectionProcessorInterface $collectionProcessor
     * @param JoinProcessorInterface $extensionAttributesJoinProcessor
     * @param ExtensibleDataObjectConverter $extensibleDataObjectConverter
     */
    public function __construct(
        ResourceStock $resource,
        StockFactory $stockFactory,
        StockInterfaceFactory $dataStockFactory,
        StockCollectionFactory $stockCollectionFactory,
        StockSearchResultsInterfaceFactory $searchResultsFactory,
        DataObjectHelper $dataObjectHelper,
        DataObjectProcessor $dataObjectProcessor,
        StoreManagerInterface $storeManager,
        CollectionProcessorInterface $collectionProcessor,
        JoinProcessorInterface $extensionAttributesJoinProcessor,
        ExtensibleDataObjectConverter $extensibleDataObjectConverter
    ) {
        $this->resource = $resource;
        $this->stockFactory = $stockFactory;
        $this->stockCollectionFactory = $stockCollectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->dataObjectHelper = $dataObjectHelper;
        $this->dataStockFactory = $dataStockFactory;
        $this->dataObjectProcessor = $dataObjectProcessor;
        $this->storeManager = $storeManager;
        $this->collectionProcessor = $collectionProcessor;
        $this->extensionAttributesJoinProcessor = $extensionAttributesJoinProcessor;
        $this->extensibleDataObjectConverter = $extensibleDataObjectConverter;
    }

    /**
     * {@inheritdoc}
     */
    public function save(
        \Adtones\Stock\Api\Data\StockInterface $stock
    ) {
        /* if (empty($stock->getStoreId())) {
            $storeId = $this->storeManager->getStore()->getId();
            $stock->setStoreId($storeId);
        } */
        
        $stockData = $this->extensibleDataObjectConverter->toNestedArray(
            $stock,
            [],
            \Adtones\Stock\Api\Data\StockInterface::class
        );
        
        $stockModel = $this->stockFactory->create()->setData($stockData);
        
        try {
            $this->resource->save($stockModel);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__(
                'Could not save the stock: %1',
                $exception->getMessage()
            ));
        }
        return $stockModel->getDataModel();
    }

    /**
     * {@inheritdoc}
     */
    public function get($stockId)
    {
        $stock = $this->stockFactory->create();
        $this->resource->load($stock, $stockId);
        if (!$stock->getId()) {
            throw new NoSuchEntityException(__('Stock with id "%1" does not exist.', $stockId));
        }
        return $stock->getDataModel();
    }

    /**
     * {@inheritdoc}
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $criteria
    ) {
        $collection = $this->stockCollectionFactory->create();
        
        $this->extensionAttributesJoinProcessor->process(
            $collection,
            \Adtones\Stock\Api\Data\StockInterface::class
        );
        
        $this->collectionProcessor->process($criteria, $collection);
        
        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($criteria);
        
        $items = [];
        foreach ($collection as $model) {
            $items[] = $model->getDataModel();
        }
        
        $searchResults->setItems($items);
        $searchResults->setTotalCount($collection->getSize());
        return $searchResults;
    }

    /**
     * {@inheritdoc}
     */
    public function delete(
        \Adtones\Stock\Api\Data\StockInterface $stock
    ) {
        try {
            $stockModel = $this->stockFactory->create();
            $this->resource->load($stockModel, $stock->getStockId());
            $this->resource->delete($stockModel);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__(
                'Could not delete the Stock: %1',
                $exception->getMessage()
            ));
        }
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function deleteById($stockId)
    {
        return $this->delete($this->get($stockId));
    }
}
