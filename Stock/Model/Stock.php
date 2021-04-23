<?php


namespace Adtones\Stock\Model;

use Adtones\Stock\Api\Data\StockInterface;
use Adtones\Stock\Api\Data\StockInterfaceFactory;
use Magento\Framework\Api\DataObjectHelper;

class Stock extends \Magento\Framework\Model\AbstractModel
{

    protected $stockDataFactory;

    protected $dataObjectHelper;

    protected $_eventPrefix = 'adtones_stock_stock';

    /**
     * @param \Magento\Framework\Model\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param StockInterfaceFactory $stockDataFactory
     * @param DataObjectHelper $dataObjectHelper
     * @param \Adtones\Stock\Model\ResourceModel\Stock $resource
     * @param \Adtones\Stock\Model\ResourceModel\Stock\Collection $resourceCollection
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        StockInterfaceFactory $stockDataFactory,
        DataObjectHelper $dataObjectHelper,
        \Adtones\Stock\Model\ResourceModel\Stock $resource,
        \Adtones\Stock\Model\ResourceModel\Stock\Collection $resourceCollection,
        array $data = []
    ) {
        $this->stockDataFactory = $stockDataFactory;
        $this->dataObjectHelper = $dataObjectHelper;
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
    }

    /**
     * Retrieve stock model with stock data
     * @return StockInterface
     */
    public function getDataModel()
    {
        $stockData = $this->getData();
        
        $stockDataObject = $this->stockDataFactory->create();
        $this->dataObjectHelper->populateWithArray(
            $stockDataObject,
            $stockData,
            StockInterface::class
        );
        
        return $stockDataObject;
    }
}
