<?php
namespace Excellence\Slider\Controller\Adminhtml\Slides;
class Edit extends \Magento\Backend\App\Action
{
   
    protected $resultRedirectFactory = false;
    protected $registry = false;
    protected $slidesFactory = false;
    protected $resultPageFactory = false;

    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Registry $registry,
        \Excellence\Slider\Model\SlidesFactory $slidesFactory,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
        $this->resultRedirectFactory = $context->getResultRedirectFactory();
        $this->registry = $registry;
        $this->slidesFactory = $slidesFactory;
    }
    public function execute()
    {
        $id = $this->getRequest()->getParam('id');
        
        $model = $this->slidesFactory->create();
        if ($id) {
            $model->load($id);
        }
        $this->registry->register('slides_edit',$model);
        
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Excellence_Slider::excellence_slider_manage_slides')
            ->addBreadcrumb(__('Manage'), __('Slides'))
            ->addBreadcrumb(__('Manage Slides'), __('Manage Slides'));
        return $resultPage;
    }
}