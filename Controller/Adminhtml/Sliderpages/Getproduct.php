<?php
namespace Excellence\Slider\Controller\Adminhtml\Sliderpages;
 
class Getproduct extends \Magento\Backend\App\Action
{
    
    protected $productFactory;
    protected $_sliderpagesFactory;

    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
        \Magento\Catalog\Model\ProductFactory $productFactory,
        \Excellence\Slider\Model\SliderpagesFactory $sliderpagesFactory
    ) {
        parent::__construct($context);
        $this->resultJsonFactory = $resultJsonFactory;
        $this->_sliderpagesFactory = $sliderpagesFactory;
        $this->productFactory = $productFactory->create();
    }
    public function execute()
    {
        $productId = $this->_sliderpagesFactory->create();
        $paramId = $this->getRequest()->getParam('id');
        $proId = $productId->load($paramId)->getData('slider_specific_display_page_product');
        $product = $this->productFactory->load($proId);
        return $this->resultJsonFactory->create()->setData([
            'sku' => $product -> getData('sku'),
            'id' => $paramId
        ]);
    }

}