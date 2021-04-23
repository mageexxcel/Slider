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
class OrderStock extends \Magento\Framework\View\Element\UiComponent\DataProvider\DataProvider
{
    /**
     * @param SearchResultInterface $searchResult
     * @return array
     */
    protected function searchResultToOutput(SearchResultInterface $searchResult)
    {
        $arrItems = [];
        $arrItems['items'] = [];

        if($_COOKIE['allocate_product_id']){
            $searchResult->addFieldToFilter("product_id", array("eq" => $_COOKIE['allocate_product_id']));
        }

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
}