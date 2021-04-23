<?php

namespace Adtones\Stock\Plugin;

class AfterCatalogEditController extends \Magento\Catalog\Controller\Adminhtml\Product\Edit
{
    /**
     * @var \Magento\Framework\App\Request\Http
     */
    protected $_request;
    /**
     * @var \Magento\Catalog\Model\Session
     */
    protected $_catalogSession;

    /**
     * @param \Magento\Framework\App\Request\Http $request
     * @param \Magento\Catalog\Model\Session $catalogSession
     */
    public function __construct(
        \Magento\Framework\App\Request\Http $request,
        \Magento\Catalog\Model\Session $catalogSession
    ) {
        $this->_request = $request;
        $this->_catalogSession = $catalogSession;
    }

    /**
     * @var | null
     * setting up 
     */
	public function beforeExecute(\Magento\Catalog\Controller\Adminhtml\Product\Edit $subject)
	{
        $this->_catalogSession->unsCurrentProductId();
        $productId = $this->_request->getParam('id');

        if($productId){
            $this->_catalogSession->setCurrentProductId($productId);
            return;
        }
	}
}