<?php
namespace Excellence\Slider\Controller\Adminhtml\Sliderpages;
 
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
        $resultPage->setActiveMenu('Excellence_Slider::excellence_slider_manage_slider_pages');
        $resultPage->addBreadcrumb(__('Manage'), __('Manage'));
        $resultPage->addBreadcrumb(__('Slider'), __('Slider'));
        $resultPage->addBreadcrumb(__('Pages'), __('Pages'));
        $resultPage->getConfig()->getTitle()->prepend(__('Manage Slider Pages'));
        return $resultPage;
    }
}