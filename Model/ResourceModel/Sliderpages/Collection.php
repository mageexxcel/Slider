<?php
namespace Excellence\Slider\Model\ResourceModel\Sliderpages;
class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
	protected $_idFieldName = 'id';
	
    protected function _construct()
    {
        $this->_init('Excellence\Slider\Model\Sliderpages','Excellence\Slider\Model\ResourceModel\Sliderpages');
    }
}
