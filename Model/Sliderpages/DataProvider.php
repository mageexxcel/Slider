<?php

namespace Excellence\Slider\Model\Sliderpages;

use Excellence\Slider\Model\ResourceModel\Sliderpages\CollectionFactory;
use Magento\Framework\App\Request\DataPersistorInterface;

class DataProvider extends \Magento\Ui\DataProvider\AbstractDataProvider
{
    protected $collection;


    protected $loadedData;

    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $pageCollectionFactory,
        array $meta = [],
        array $data = []
    ) {
        $this->collection = $pageCollectionFactory->create();
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
    }

    public function getData()
    {
        if (isset($this->loadedData)) {
            return $this->loadedData;
        }
        foreach ($this->collection->getAllIds() as $pageId) {
            $this->loadedData[$pageId]['sliderpages'] = $page->load($pageId)->getData();
        }

        return $this->loadedData;
    }
}




