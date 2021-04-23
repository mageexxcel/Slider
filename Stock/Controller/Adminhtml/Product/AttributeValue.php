<?php
namespace Adtones\Stock\Controller\Adminhtml\Product;

class AttributeValue extends \Adtones\Stock\Controller\Adminhtml\Stock
{
    /**
     * @var \Adtones\Stock\Helper\Data
     */
    protected $_customHelper;
    /**
     * @var \Magento\Framework\Registry
     */
    protected $_registry;
    /**
     * @var \Magento\Framework\App\Request\Http
     */
    protected $request;
    /**
     * @var \Magento\Framework\Controller\Result\JsonFactory
     */
    private $jsonResultFactory;
    
    /**
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Adtones\Stock\Helper\Data $customHelper
     * @param \Magento\Framework\Registry $coreRegistry
     * @param \Magento\Framework\App\Request\Http $request
     * @param \Magento\Framework\Controller\Result\JsonFactory $jsonResultFactory,
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Adtones\Stock\Helper\Data $customHelper,
        \Magento\Framework\Registry $coreRegistry,
        \Magento\Framework\App\Request\Http $request,
        \Magento\Framework\Controller\Result\JsonFactory $jsonResultFactory
    ) {
        $this->_customHelper = $customHelper;
        $this->request = $request;
        $this->jsonResultFactory = $jsonResultFactory;
        parent::__construct($context, $coreRegistry);
    }

    /**
     * New action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        $result = $this->jsonResultFactory->create();

        if($this->request->getParam('currentProductId')){
            $currentProductId = $this->request->getParam('currentProductId');
        }

        if($currentProductId){
            // Getting products attribute and sending back in json array.
            $productCollection = $this->_customHelper->getProductAttribute($currentProductId);
            $work = $productCollection->getName();
            $productName = $productCollection->getName();
            $sku = $productCollection->getSku();

            // Rendering category name for Artist column.
            $categoryName = '';
            $categories = $productCollection->getCategoryIds();
            foreach($categories as $category){
                $catName = $this->_customHelper->getCategoriesCollection($category);
                $categoryName = $catName->getName();
            }
            $artist = $categoryName;

            $jsonArray = array(
                'artist'       => $artist, 
                'work'         => $work,
                'product_name' => $productName,
                'sku'          => $sku,
            );            
            $result->setData($jsonArray);
            return $result;
        }
        return;
    }
}