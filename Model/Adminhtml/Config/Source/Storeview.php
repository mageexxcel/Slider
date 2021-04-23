<?php

namespace Excellence\Slider\Model\Adminhtml\Config\Source;
 
class Storeview implements \Magento\Framework\Option\ArrayInterface
{
	public function toOptionArray()
    {
    	/** @var \Magento\Framework\App\ObjectManager $objectManager */
		$objectManager = \Magento\Framework\App\ObjectManager::getInstance();
		/** @var \Magento\Store\Model\StoreManagerInterface|\Magento\Store\Model\StoreManager $storeManager */
		$storeManager = $objectManager->get('Magento\Store\Model\StoreManagerInterface');
		$stores = $storeManager->getStores();
		$optionArray = array(array('value' => '0', 'label' => 'All Store Views'));
		foreach ($stores as $store) {
			array_push($optionArray, array('value' => $store->getId(), 'label' => $store->getName()));
		}

        return $optionArray;

    }
}