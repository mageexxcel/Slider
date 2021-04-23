<?php

namespace Excellence\Slider\Controller\Adminhtml\Sliderpages;

class Delete extends \Magento\Backend\App\Action
{
    protected $resultPageFactory = false;
    protected $resultRedirectFactory = false;
    protected $_sliderpagesFactory = false;
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Excellence\Slider\Model\SliderpagesFactory $sliderpagesFactory
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
        $this->resultRedirectFactory = $context->getResultRedirectFactory();
        $this->_sliderpagesFactory = $sliderpagesFactory;
    }
    public function execute()
    {
        $id = $this->getRequest()->getParam('id');
        $model = $this->_sliderpagesFactory->create();
        if ($id) {
            $model->load($id);
            if($model->delete()){
                $this->messageManager->addSuccess(__('Slider Has Been Deleted.'));
            }
            else{
                $this->messageManager->addError(__('Some error occured while deleting the slider. Please try again.'));
            }
        }

        $resultRedirect = $this->resultRedirectFactory->create();
        $resultRedirect->setPath('*/*/');
        return $resultRedirect;
    }
}