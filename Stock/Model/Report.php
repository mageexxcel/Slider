<?php


namespace Adtones\Stock\Model;

use Adtones\Stock\Api\Data\ReportInterface;
use Adtones\Stock\Api\Data\ReportInterfaceFactory;
use Magento\Framework\Api\DataObjectHelper;

class Report extends \Magento\Framework\Model\AbstractModel
{

    protected $reportDataFactory;

    protected $dataObjectHelper;

    protected $_eventPrefix = 'adtones_stock_report';

    /**
     * @param \Magento\Framework\Model\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param ReportInterfaceFactory $reportDataFactory
     * @param DataObjectHelper $dataObjectHelper
     * @param \Adtones\Stock\Model\ResourceModel\Report $resource
     * @param \Adtones\Stock\Model\ResourceModel\Report\Collection $resourceCollection
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        ReportInterfaceFactory $reportDataFactory,
        DataObjectHelper $dataObjectHelper,
        \Adtones\Stock\Model\ResourceModel\Report $resource,
        \Adtones\Stock\Model\ResourceModel\Report\Collection $resourceCollection,
        array $data = []
    ) {
        $this->reportDataFactory = $reportDataFactory;
        $this->dataObjectHelper = $dataObjectHelper;
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
    }

    /**
     * Retrieve report model with report data
     * @return ReportInterface
     */
    public function getDataModel()
    {
        $reportData = $this->getData();
        
        $reportDataObject = $this->reportDataFactory->create();
        $this->dataObjectHelper->populateWithArray(
            $reportDataObject,
            $reportData,
            ReportInterface::class
        );
        
        return $reportDataObject;
    }
}
