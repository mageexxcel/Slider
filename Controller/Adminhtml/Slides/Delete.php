<?php

namespace Excellence\Slider\Controller\Adminhtml\Slides;

class Delete extends \Magento\Backend\App\Action
{
    protected $resultPageFactory = false;
    protected $resultRedirectFactory = false;
    protected $_slidesFactory = false;
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Excellence\Slider\Model\SlidesFactory $slidesFactory
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
        $this->resultRedirectFactory = $context->getResultRedirectFactory();
        $this->_slidesFactory = $slidesFactory;
    }
    public function execute()
    {
        $id = $this->getRequest()->getParam('id');
        $model = $this->_slidesFactory->create();
        if ($id) {
            $model->load($id);
            if($model->delete()){
                $this->messageManager->addSuccess(__('Slide Has Been Deleted.'));
            }
            else{
                $this->messageManager->addError(__('Some error occured while deleting the slide. Please try again.'));
            }
        }
        $resultRedirect = $this->resultRedirectFactory->create();
        $resultRedirect->setPath('*/*/');
        return $resultRedirect;
    }
}