<?php
namespace Excellence\Slider\Model;

class Slides extends \Magento\Framework\Model\AbstractModel implements SlidesInterface, \Magento\Framework\DataObject\IdentityInterface
{
	protected $_collectionFactory;

    const CACHE_TAG = 'excellence_slider_slides';

    protected function _construct()
    {
        $this->_init('Excellence\Slider\Model\ResourceModel\Slides');
    }

    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }
}
