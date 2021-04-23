<?php
namespace Excellence\Slider\Controller\Adminhtml\Slides;
 
 
class Index extends \Magento\Backend\App\Action
{
    
    protected $resultPageFactory = false;
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
    }
    public function execute()
    {
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Excellence_Slider::excellence_slider_manage_slides');
        $resultPage->addBreadcrumb(__('Manage'), __('Manage'));
        $resultPage->addBreadcrumb(__('Slides'), __('Slides'));
        $resultPage->getConfig()->getTitle()->prepend(__('Manage Slides'));
        return $resultPage;
    }
}