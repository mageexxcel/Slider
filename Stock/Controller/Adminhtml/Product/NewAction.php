<?php
/**
 *
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Adtones\Stock\Controller\Adminhtml\Product;

use Magento\Framework\App\Action\HttpGetActionInterface as HttpGetActionInterface;
use Magento\Backend\App\Action;
use Magento\Catalog\Controller\Adminhtml\Product;
use Magento\Framework\App\ObjectManager;

class NewAction extends \Magento\Catalog\Controller\Adminhtml\Product implements HttpGetActionInterface
{
    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $resultPageFactory;

    /**
     * @var \Magento\Backend\Model\View\Result\ForwardFactory
     */
    protected $resultForwardFactory;
    /**
     * @var \Magento\Catalog\Model\Session
     */
    protected $_catalogSession;

    /**
     * @param Action\Context $context
     * @param Builder $productBuilder
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     * @param \Magento\Backend\Model\View\Result\ForwardFactory $resultForwardFactory
     * @param \Magento\Catalog\Model\Session $catalogSession
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        Product\Builder $productBuilder,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Backend\Model\View\Result\ForwardFactory $resultForwardFactory,
        \Magento\Catalog\Model\Session $catalogSession
    ) {
        parent::__construct($context, $productBuilder);
        $this->resultPageFactory = $resultPageFactory;
        $this->resultForwardFactory = $resultForwardFactory;
        $this->_catalogSession = $catalogSession;
    }

    /**
     * Create new product page
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        // Removing Catalog session if admin creating new product. 
        $this->_catalogSession->unsCurrentProductId();
        
        if (!$this->getRequest()->getParam('set')) {
            return $this->resultForwardFactory->create()->forward('noroute');
        }

        $product = $this->productBuilder->build($this->getRequest());
        $this->_eventManager->dispatch('catalog_product_new_action', ['product' => $product]);

        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        if ($this->getRequest()->getParam('popup')) {
            $resultPage->addHandle(['popup', 'catalog_product_' . $product->getTypeId()]);
        } else {
            $resultPage->addHandle(['catalog_product_' . $product->getTypeId()]);
            $resultPage->setActiveMenu('Magento_Catalog::catalog_products');
            $resultPage->getConfig()->getTitle()->prepend(__('Products'));
            $resultPage->getConfig()->getTitle()->prepend(__('New Product'));
        }

        return $resultPage;
    }
}
