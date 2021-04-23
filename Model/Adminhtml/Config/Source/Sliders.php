<?php

namespace Excellence\Slider\Model\Adminhtml\Config\Source;

use Excellence\Slider\Model\ResourceModel\Sliderpages\CollectionFactory;
 
class Sliders implements \Magento\Framework\Option\ArrayInterface
{

    protected $collection;

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        CollectionFactory $pageCollectionFactory
    ) {
        $this->collection = $pageCollectionFactory->create();
    }

    public function toOptionArray()
    {
        $optionArray = array();
        foreach ($this->collection->getData() as $data) {
            array_push($optionArray, array('value' => $data['id'], 'label' => $data['slider_name']));
        }
        return $optionArray;  
    }   
}