<?php


namespace Adtones\Stock\Api\Data;

interface ReportSearchResultsInterface extends \Magento\Framework\Api\SearchResultsInterface
{

    /**
     * Get Stock list.
     * @return \Adtones\Stock\Api\Data\ReportInterface[]
     */
    public function getItems();

    /**
     * Set entity_id list.
     * @param \Adtones\Stock\Api\Data\ReportInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
