<?php
namespace Excellence\Slider\Block;

use Excellence\Slider\Model\Adminhtml\Config\Source\Status;
use Excellence\Slider\Model\Adminhtml\Config\Source\Pages;
use Excellence\Slider\Model\ResourceModel\Slides\CollectionFactory;
use Excellence\Slider\Model\SliderpagesFactory;
use Excellence\Slider\Model\SlidesFactory;
  
class Slider extends \Magento\Framework\View\Element\Template
{   
	protected $_collectionFactory;
    protected $_sliderPagesFactory;
    protected $_scopeConfigObject;
    protected $_page;
    protected $_registry;
    protected $_slidesFactory;
    protected $_messageManager;

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfigObject,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        CollectionFactory $collectionFactory,
        SliderpagesFactory $sliderPagesFactory,
        SlidesFactory $sliderFactory,
        \Magento\Cms\Model\Page $page,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Message\ManagerInterface $messageManager,
        array $data = []
    )
    {
        $this->_scopeConfigObject = $scopeConfigObject;
        $this->_storeManager = $storeManager;
    	$this->_collectionFactory = $collectionFactory;
        $this->_sliderPagesFactory = $sliderPagesFactory;
        $this->_slidesFactory = $sliderFactory;
        $this->_page = $page;
        $this->_registry = $registry;
        $this->_messageManager = $messageManager;
        parent::__construct($context, $data);
    }
    public function getStretchImageInfo($sliderId){
        if(!empty($sliderId)){
            $sliderDetails =  $this->_sliderPagesFactory->create()->getCollection()->addFieldToFilter('id',$sliderId);
            foreach ($sliderDetails as $sliderDataInfo) {
               $stretchImageVal = $sliderDataInfo->getStretchImage();
            }
            return $stretchImageVal;
        }
    }
    
    public function setErrorMessage($args){
        switch ($args){
            case 400 :
                    $message = 'Slider Id either not passed or invalid !';
                    // $this->_messageManager->addError($message);
                    break;
            case 500:
                    $message = 'Provided slider id is not configured for custom location, configure it first or may be it is disable from config. !';
                    // $this->_messageManager->addError($message);
                    break;
        }
        
    }

    public function setSlider($position)
    {
        if(!($this->_scopeConfigObject->getValue('slider/slider/enable'))){
            return;
        } 
        $storeId = $this->_storeManager->getStore()->getId();
        $sliderId = $this->_sliderPagesFactory->create();
    
        if(!empty($this->_registry->registry('current_category'))){
            //category page
            $category = $this->_registry->registry('current_category');
            $slider_id = $sliderId->getSliderId(Pages::CATEGORY_PAGE, $category->getID(), $position, $storeId);
        }    
        if(!empty($this->_registry->registry('current_product'))){
            //product page
            $product = $this->_registry->registry('current_product');
            $slider_id = $sliderId->getSliderId(Pages::PRODUCT_PAGE, $product->getID(), $position, $storeId);
        }
        if(!empty($this->_page->getId())){
            $slider_id = $sliderId->getSliderId(Pages::CMS_PAGE, $this->_page->getId(), $position, $storeId);
            //cms page
            
        }
        if(!empty($slider_id)){
            $slides = $this->_collectionFactory->create()
                ->addFieldToFilter('is_active', Status::STATUS_ENABLED)
                ->addFieldToFilter('slider_name', $slider_id)
                ->setOrder('image_position', 'ASC');
            $collectionData = $slides->getData();
        }else{
            $collectionData = null;
        }
        $this->setSlidesModel($collectionData);
        $sliderType = $this->_scopeConfigObject->getValue('slider/slider/select_slider');
        $this->setSliderType($sliderType);
        $this->setSliderPositionClass($this->getSliderClass($position));
    }

    public function setSliderForCustomLocation($sliderId)
    {
        if(!($this->_scopeConfigObject->getValue('slider/slider/enable'))){
            return;
        }
        $storeId = $this->_storeManager->getStore()->getId();
        $slider = $this->_sliderPagesFactory->create()->getCollection()
            ->addFieldToFilter('is_active', '1')
            ->addFieldToFilter('id', $sliderId)
            ->addFieldToFilter('slider_display_page', '7')
            ->addFieldToFilter('store_id',array('like' => '%'.$storeId.'%'));
        $collectionData = $slider->getData();
        if(!empty($collectionData)){
            /*
            found entry for particular id
            and calling method to grab collection of images 
            belonging o this particular slider id
            */
            $imageCollection = $this->setSlidesForCustomLocation($sliderId);
            return $imageCollection;
        }
    }


    public function setSlidesForCustomLocation($sliderId){
        /*
        control comes here only when your apply custom page custom 
        location slider by id in overrided phtml
        */
        $slides = $this->_slidesFactory->create()->getCollection()
        ->addFieldToFilter('is_active', '1')
        ->addFieldToFilter('slider_name', $sliderId);
        return $slides->getData();
    }

    public function checkoutPageSlider($pageTypeId, $position)
    {
        if(!($this->_scopeConfigObject->getValue('slider/slider/enable'))){
            return;
        }
        $storeId = $this->_storeManager->getStore()->getId();
        $sliderId = $this->_sliderPagesFactory->create();
        if($pageTypeId == Pages::CART_PAGE){
          $slider_id = $sliderId->getSliderId(Pages::CART_PAGE,'', $position, $storeId);
        }
        if($pageTypeId == Pages::CHECKOUT_PAGE){
          $slider_id = $sliderId->getSliderId(Pages::CHECKOUT_PAGE,'', $position, $storeId);
        }
        $slides = $this->_collectionFactory->create()
            ->addFieldToFilter('is_active', Status::STATUS_ENABLED)
            ->addFieldToFilter('slider_name', $slider_id)
            ->setOrder('image_position', 'ASC');
        $collectionData = $slides->getData();
        $this->setSlidesModel($collectionData);
        $sliderType = $this->_scopeConfigObject->getValue('slider/slider/select_slider');
        $this->setSliderType($sliderType);
        $this->setSliderPositionClass($this->getSliderClass($position));
    }
    public function getSliderClass($position){
        $positionArray = [['value' => 1, 'class' => 'top-left'], 
            ['value' => 2, 'class' => 'bottom-left'],
            ['value' => 3, 'class' => 'top-center'],
            ['value' => 4, 'class' => 'bottom-center'],
            ['value' => 5, 'class' => 'top-right'],
            ['value' => 6, 'class' => 'bottom-right']
            ];
        $sliderContainerClass = '';
        foreach ($positionArray as $positionData) {
            if($positionData['value'] == $position){
                $sliderContainerClass = $positionData['class'];
                break;
            }
        }
        return $sliderContainerClass;
    }
    public function getSliderDetails(){
        if(!($this->_scopeConfigObject->getValue('slider/slider/enable'))){
            return;
        }
        $sliderInfo['sliderType'] = $this->_scopeConfigObject->getValue('slider/slider/select_slider');
        $sliderSpeed = $this->_scopeConfigObject->getValue('slider/slider/select_speed');
        $sliderInfo['sliderSpeed'] = ($sliderSpeed * 1000);
        $sliderInfo['fade'] = $this->_scopeConfigObject->getValue('slider/slider/select_fade');
        $sliderInfo['pauseonhover'] = $this->_scopeConfigObject->getValue('slider/slider/select_pauseonhover');
        $sliderInfo['loop'] = $this->_scopeConfigObject->getValue('slider/slider/select_loop');
        $sliderInfo['dots'] = $this->_scopeConfigObject->getValue('slider/slider/select_dots');
        $sliderInfo['imagecounts'] = $this->_scopeConfigObject->getValue('slider/slider/select_image_numbers');
        $sliderInfo['autoplay'] = $this->_scopeConfigObject->getValue('slider/slider/select_autoplay');
        $sliderInfo['caption'] = $this->_scopeConfigObject->getValue('slider/slider/select_caption');
        $sliderInfo['infiniteLoop'] = $this->_scopeConfigObject->getValue('slider/slider/select_infinite');
        $sliderInfo['adaptiveHeight'] = $this->_scopeConfigObject->getValue('slider/slider/select_adaptive_height');
        $sliderInfo['controlNavDots'] = $this->_scopeConfigObject->getValue('slider/slider/select_control_nav');

        return $sliderInfo;
    }
    public function getFadeval($sliderFadeVals){
        if($sliderFadeVals == 1)
        {
            return 'fade';
        }else{
            return 'horizontal';
        }
    }
    function getPauseOnHover($sliderPauseHover){
        if($sliderPauseHover == 1)
        {
            return "true";
        }else{
            return "false";
        }
    }
    function getLoopInfo($sliderLoopThrough){
        if($sliderLoopThrough == 1){
            return "true";
        }else{
            return "false";
        }
    }
    function getNavDot($sliderNavDots){
        if($sliderNavDots == 1){
            return "true";
        }else{
            return "false";
        }
    }
    function getAutoPlayVal($autoPlay){
        if($autoPlay == 1){
            return "true";
        }else{
            return "false";
        }
    }
    function getCaption($caption){
        if($caption == 1){
            return "true";
        }else{
            return "false";
        }
    }
    function getInfiniteLoop($infiniteLoop){
        if($infiniteLoop){
            return "true";
        }else{
            return "false";
        }
    }
    function getAdaptiveHeight($adaptiveHeight){
        if($adaptiveHeight){
            return "true";
        }else{
            return "false";
        }
    }
    function getControlNavDots($controlNavDots){
        if($controlNavDots){
            return "true";
        }else{
            return "false";
        }
    }

}