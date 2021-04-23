<?php


namespace Adtones\Stock\Model\ResourceModel\Stock;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(
            \Adtones\Stock\Model\Stock::class,
            \Adtones\Stock\Model\ResourceModel\Stock::class
        );
    }
}
