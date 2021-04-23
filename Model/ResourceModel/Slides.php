<?php
namespace Excellence\Slider\Model\ResourceModel;
class Slides extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    protected function _construct()
    {
        $this->_init('excellence_slider_slides','id');
    }
}
