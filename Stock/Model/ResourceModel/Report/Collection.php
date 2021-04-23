<?php


namespace Adtones\Stock\Model\ResourceModel\Report;

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
            \Adtones\Stock\Model\Report::class,
            \Adtones\Stock\Model\ResourceModel\Report::class
        );
    }
}
