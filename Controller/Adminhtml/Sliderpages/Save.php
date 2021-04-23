<?php

namespace Excellence\Slider\Controller\Adminhtml\Sliderpages;

class Save extends \Magento\Backend\App\Action
{
    protected $resultPageFactory = false;
    protected $resultRedirectFactory = false;
    protected $_sliderpagesFactory = false;
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
         \Magento\Backend\Helper\Data $helper,
        \Excellence\Slider\Model\SliderpagesFactory $sliderpagesFactory
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
        $this->_helper = $helper;
        $this->resultRedirectFactory = $context->getResultRedirectFactory();
        $this->_sliderpagesFactory = $sliderpagesFactory;
    }
    public function execute()
    {
        $post = $this->getRequest()->getPostValue();
        
        $isEdit = false;
        if(isset($post)){
            $data = $this->_sliderpagesFactory->create();
            if(!empty($post['id'])){
                $data->load($post['id']);
                $isEdit = true;
            }
            if(isset($post['slider_specific_display_page_category']))
            {
                $jsonSliderPage = json_encode($post['slider_specific_display_page_category']);
                $post['slider_specific_display_page_category'] = $jsonSliderPage;
            }
            // For Multiselect Products
            if(isset($post['slider_specific_display_page_product']))
            {
            $convertStringToArray = explode(",", rtrim($post['slider_specific_display_page_product'], ","));
            $jsonSliderPage = json_encode($convertStringToArray);

            $post['slider_specific_display_page_product'] = $jsonSliderPage;
            }
            unset($post['form_key']);
            $post['stores'] = implode(',',$this->getRequest()->getParam('stores'));
            $data->setData($post);
            if($data->save()){
                $data->setStoreId($post['stores'])->save();
                if($isEdit){
                    $this->messageManager->addSuccess(__("Slider <strong>".$data->getSliderName()."</strong> has been updated."));
                }
                else{
                    $this->messageManager->addSuccess(__("Your Slider has been created. <a href='".$this->_helper->getUrl('slider/slides')."' >".__("Click here")."</a> to add slides next to complete."));
                }   
            }
            else{
                $this->messageManager->addError(__('Some error occured while saving the slider. Please try again.'));
            }
            if ($this->getRequest()->getParam('back')) {
                $this->_redirect('*/*/edit', ['id' => $data->getId(), '_current' => true]);
                return;
            }
        }
        $resultRedirect = $this->resultRedirectFactory->create();
        $resultRedirect->setPath('*/*/');
        return $resultRedirect;
    }
}