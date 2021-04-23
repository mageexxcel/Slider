<?php
namespace Excellence\Slider\Controller\Adminhtml\Sliderpages;
class NewAction extends \Magento\Backend\App\Action
{
   
    protected $resultForwardFactory;
    protected $resultPageFactory = false;
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Backend\Model\View\Result\ForwardFactory $resultForwardFactory
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
        $this->resultForwardFactory = $resultForwardFactory;
    }
    public function execute()
    {
        $resultPage = $this->resultPageFactory->create();
        $resultForward = $this->resultForwardFactory->create();
        return $resultForward->forward('edit');
    }
}