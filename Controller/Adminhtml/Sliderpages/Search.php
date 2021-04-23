<?php
namespace Excellence\Slider\Controller\Adminhtml\Sliderpages;
 
class Search extends \Magento\Backend\App\Action
{
    
    protected $productFactory;

    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
        \Magento\Catalog\Model\ProductFactory $productFactory
    ) {
        parent::__construct($context);
        $this->resultJsonFactory = $resultJsonFactory;
        $this->productFactory = $productFactory->create();
    }
    public function execute()
    {
        $searchTerm = $this->getRequest()->getParam('searchTerm');
        $optionArray = array();
        if($this->productFactory->getIdBySku($searchTerm)){
            // Load the product using SKU
            $product = $this->productFactory->load($this->productFactory->getIdBySku($searchTerm));
            if($product->getVisibility() == 4){
                array_push($optionArray, array('value' => $product->getId(), 'label' => $product->getData('name')));
            }
            return $this->resultJsonFactory->create()->setData($optionArray);
        }
        $collection = $this->productFactory->getCollection()
                    ->addAttributeToFilter(
                            array(
                                array('attribute'=> 'sku','like' => '%'.$searchTerm.'%'),
                                array('attribute'=> 'name','like' => '%'.$searchTerm.'%'),
                            )
                        )
                    ->addAttributeToFilter('visibility', 4)
                    ->setOrder('sku', 'asc')->getData();
        foreach ($collection as $data) {
            array_push($optionArray, array('value' => $data['entity_id'], 'label' => $this->productFactory->load($data['entity_id'])->getData('name')));
        }
        return $this->resultJsonFactory->create()->setData($optionArray);
    }

}