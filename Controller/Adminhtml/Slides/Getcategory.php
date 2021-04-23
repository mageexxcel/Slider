<?php
namespace Excellence\Slider\Controller\Adminhtml\Slides;
 
class Getcategory extends \Magento\Backend\App\Action
{
    
    protected $categoryFactory;
    protected $_slidesFactory;

    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
        \Magento\Catalog\Model\CategoryFactory $categoryFactory,
        \Excellence\Slider\Model\SlidesFactory $slidesFactory
    ) {
        parent::__construct($context);
        $this->resultJsonFactory = $resultJsonFactory;
        $this->_slidesFactory = $slidesFactory;
        $this->categoryFactory = $categoryFactory->create();
    }
    public function execute()
    {
        $categoryId = $this->getRequest()->getParam('categoryId');  
        $storeId = $this->getRequest()->getParam('storeId');
        if(isset($categoryId)){ 
            $catId = $this->_slidesFactory->create()
                        ->load($categoryId)
                        ->getData('slider_specific_display_page_category');
            return $this->resultJsonFactory->create()->setData(['selectId' => $catId]);
        }
        $optionArray = array();
        $collection = $this->categoryFactory
                        ->getCollection()
                        ->addAttributeToSort('path', 'asc')
                        ->getData();

        foreach ($collection as $data) {
            if($data['entity_id'] > 2){
                $spaces = "";
                for($i=0; $i<$data['level']-2; $i++){
                    $spaces .= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                }
                $category = $this->categoryFactory->load($data['entity_id']);
                $categoryName = $category->getData('name');
                if (!($category->getIsActive())) {
                    $categoryName .= " ---Disabled";
                }
                array_push($optionArray, array('value' => $data['entity_id'], 'label' => $spaces.$categoryName));
            }
            
        }
        return $this->resultJsonFactory->create()->setData($optionArray);
    }

}