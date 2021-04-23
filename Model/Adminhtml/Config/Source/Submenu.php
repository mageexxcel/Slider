<?php

namespace Excellence\Slider\Model\Adminhtml\Config\Source;

use Excellence\Slider\Model\ResourceModel\Sliderpages\CollectionFactory;
 
class Submenu  extends \Magento\Framework\View\Element\Template
{

    protected $collection;
    protected $_page;

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Cms\Model\Page $page,
        array $data = []
    ) {
        $this->_page = $page;
    }

    public function getCmsPage()
    {
        $pages = $this->_page->create();
        $optionArray = array();
        foreach ($pages->getData() as $data) {
            array_push($optionArray, array('value' => $data['id'], 'label' => $data['title']));
        }
        return $optionArray;  
    }   
}