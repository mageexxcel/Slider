<?php


namespace Adtones\Stock\Api;

use Magento\Framework\Api\SearchCriteriaInterface;

interface StockRepositoryInterface
{

    /**
     * Save Stock
     * @param \Adtones\Stock\Api\Data\StockInterface $stock
     * @return \Adtones\Stock\Api\Data\StockInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(
        \Adtones\Stock\Api\Data\StockInterface $stock
    );

    /**
     * Retrieve Stock
     * @param string $stockId
     * @return \Adtones\Stock\Api\Data\StockInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function get($stockId);

    /**
     * Retrieve Stock matching the specified criteria.
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Adtones\Stock\Api\Data\StockSearchResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
    );

    /**
     * Delete Stock
     * @param \Adtones\Stock\Api\Data\StockInterface $stock
     * @return bool true on success
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function delete(
        \Adtones\Stock\Api\Data\StockInterface $stock
    );

    /**
     * Delete Stock by ID
     * @param string $stockId
     * @return bool true on success
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteById($stockId);
}
