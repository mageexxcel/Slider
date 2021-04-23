<?php


namespace Adtones\Stock\Api\Data;

interface StockSearchResultsInterface extends \Magento\Framework\Api\SearchResultsInterface
{

    /**
     * Get Stock list.
     * @return \Adtones\Stock\Api\Data\StockInterface[]
     */
    public function getItems();

    /**
     * Set entity_id list.
     * @param \Adtones\Stock\Api\Data\StockInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
