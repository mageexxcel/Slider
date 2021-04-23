<?php

namespace Excellence\Slider\Model\Adminhtml\Config\Source;

use Excellence\Slider\Model\ResourceModel\Sliderpages\CollectionFactory;
 
class Specialpage implements \Magento\Framework\Option\ArrayInterface
{

    protected $collection;

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Catalog\Model\ProductFactory $productFactory
    ) {
        $this->productFactory = $productFactory->create();
    }

    public function toOptionArray()
    {
         $optionArray = array();
        array_push($optionArray, array('value' => 0, 'label' => '-- Select --'));
        foreach ($this->productFactory->getCollection()->getData() as $data) {
            array_push($optionArray, array('value' => $data['entity_id'], 'label' => $this->productFactory->load($data['entity_id'])->getData('name')));
        }
        return $optionArray; 
    }   
}