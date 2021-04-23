<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Adtones\Stock\Model\UiComponent\DataProvider;

use Magento\Framework\Api\Search\SearchResultInterface;

/**
 * Class DataProvider
 */
class DataProvider extends \Magento\Framework\View\Element\UiComponent\DataProvider\DataProvider
{
    /**
     * @param SearchResultInterface $searchResult
     * @return array
     */
    protected function searchResultToOutput(SearchResultInterface $searchResult)
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $product = $objectManager->get('\Magento\Catalog\Model\Session');
        
        $arrItems = [];
        $arrItems['items'] = [];
        
        if($product->getCurrentProductId()){
            $searchResult->addFieldToFilter("product_id", array("eq" => $product->getCurrentProductId()));

            foreach ($searchResult->getItems() as $item) {
                $itemData = [];
                foreach ($item->getCustomAttributes() as $attribute) {
                    $itemData[$attribute->getAttributeCode()] = $attribute->getValue();
                }
                $arrItems['items'][] = $itemData;
            }
            $arrItems['totalRecords'] = $searchResult->getTotalCount();
            return $arrItems;
        }
        return $arrItems;
    }
}