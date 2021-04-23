<?php
namespace Excellence\Slider\Model;
class Sliderpages extends \Magento\Framework\Model\AbstractModel
{
    const CACHE_TAG = 'excellence_slider_sliderpages';

    protected function _construct()
    {
        $this->_init('Excellence\Slider\Model\ResourceModel\Sliderpages');
    }

    public function getSliderId($pageTypeId, $currentPageId, $position, $storeId)
    {
    	$id = $this->getResource()->getSliderId($pageTypeId, $currentPageId, $position, $storeId);
    	return $id;
    }
}
